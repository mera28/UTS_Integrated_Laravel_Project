<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Support\FormatHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    /**
     * Retrieve paginated purchases with multi-criteria filters.
     *
     * @param array{search?: string|null, supplier_id?: int|string|null, status?: string|null, start_date?: string|null, end_date?: string|null} $filters
     */
    public function getFilteredPurchases(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Purchase::with(['supplier', 'user'])
            ->withCount('items')
            ->latest('purchased_at');

        // Search by PO number or supplier name
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Supplier filter
        if (! empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        // Status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date range filter
        if (! empty($filters['start_date'])) {
            $query->whereDate('purchased_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('purchased_at', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a purchase order with stock increments in an atomic DB transaction.
     *
     * @param array{supplier_id: int, purchased_at?: string|null, notes?: string|null, items: array<int, array{product_id: int, quantity: int, unit_cost: float}>} $data
     * @throws \Throwable
     */
    public function createPurchase(array $data, int $userId): Purchase
    {
        return DB::transaction(function () use ($data, $userId) {
            $purchaseNumber = 'PO-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
            $purchasedAt = ! empty($data['purchased_at']) ? $data['purchased_at'] : now();

            // Calculate total server-side
            $total = 0;
            $itemsToCreate = [];

            foreach ($data['items'] as $item) {
                $qty = (int) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $subtotal = $qty * $unitCost;
                $total += $subtotal;

                // Lock row to prevent concurrency race conditions
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                // Increment physical stock (Slide 7 & 8)
                $product->increment('stock', $qty);

                // Update product purchase price if necessary
                if ($unitCost > 0) {
                    $product->update(['purchase_price' => $unitCost]);
                }

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ];
            }

            // Create Master Purchase record
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => $userId,
                'purchase_number' => $purchaseNumber,
                'total' => $total,
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
                'purchased_at' => $purchasedAt,
            ]);

            // Save individual items
            foreach ($itemsToCreate as $itemData) {
                $itemData['purchase_id'] = $purchase->id;
                PurchaseItem::create($itemData);
            }

            return $purchase;
        });
    }

    /**
     * Cancel purchase transaction and reverse stock with concurrency lock (Admin Only).
     *
     * @throws ValidationException|\Throwable
     */
    public function cancelPurchase(Purchase $purchase): array
    {
        return DB::transaction(function () use ($purchase) {
            if ($purchase->status === 'cancelled') {
                throw ValidationException::withMessages([
                    'error' => "Transaksi pembelian '{$purchase->purchase_number}' sudah dibatalkan sebelumnya.",
                ]);
            }

            // Ensure stock sufficiency for all items before decrementing
            foreach ($purchase->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'error' => "Gagal membatalkan transaksi! Stok produk [{$product->sku}] '{$product->name}' saat ini tinggal {$product->stock} pcs (kurang dari {$item->quantity} pcs yang dibeli). Sebagian pakaian telah terjual ke pelanggan, sehingga stok tidak dapat ditarik kembali demi mencegah stok negatif.",
                    ]);
                }
            }

            // Safe to reverse stock
            foreach ($purchase->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $product->decrement('stock', $item->quantity);
            }

            $purchase->update(['status' => 'cancelled']);

            return [
                'success' => true,
                'message' => "Transaksi pembelian '{$purchase->purchase_number}' berhasil dibatalkan dan stok produk telah dikurangkan kembali ke gudang.",
            ];
        });
    }
}
