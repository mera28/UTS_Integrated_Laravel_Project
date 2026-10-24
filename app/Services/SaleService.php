<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    public function __construct(
        protected PaymentGatewayService $paymentGatewayService
    ) {}

    /**
     * Retrieve paginated sales transactions with multiple filters.
     *
     * @param array{search?: string|null, status?: string|null, payment_method?: string|null, payment_status?: string|null, start_date?: string|null, end_date?: string|null} $filters
     */
    public function getFilteredSales(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Sale::with(['user', 'items.product'])->latest('sold_at');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('payment_reference', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Execute sales transaction with row lock, stock decrement, and server-side pricing (Slide 16).
     *
     * @param array{customer_name?: string|null, shipping_address?: string|null, payment_method: string, is_pos?: bool, items: array<int, array{product_id: int, quantity: int, selected_size?: string|null, selected_color?: string|null}>} $data
     * @throws ValidationException|\Throwable
     */
    public function createSale(array $data, User $user): Sale
    {
        return DB::transaction(function () use ($data, $user) {
            $saleNumber = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
            $isPos = $data['is_pos'] ?? false;
            $paymentMethod = $data['payment_method'];

            $total = 0;
            $itemsToCreate = [];

            // Process every item with lockForUpdate (Slide 16)
            foreach ($data['items'] as $item) {
                $qty = (int) $item['quantity'];

                // 1. Lock product row to prevent race conditions & overselling
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                // 2. Validate stock sufficiency strictly
                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk '{$product->name}' [{$product->sku}] tidak mencukupi (Tersedia: {$product->stock} pcs, diminta: {$qty} pcs).",
                    ]);
                }

                // 3. Selling price taken strictly from database
                $unitPrice = (float) $product->selling_price;
                $subtotal = $unitPrice * $qty;
                $total += $subtotal;

                // 4. Decrement physical stock (Slide 16)
                $product->decrement('stock', $qty);

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'selected_size' => $item['selected_size'] ?? $product->size,
                    'selected_color' => $item['selected_color'] ?? $product->color,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            // Determine payment status: Cash/POS is immediately paid, Online checkout is unpaid pending simulation
            $isDirectPaid = $isPos || $paymentMethod === 'cash';
            $paymentStatus = $isDirectPaid ? 'paid' : 'unpaid';
            $saleStatus = $isDirectPaid ? 'completed' : 'pending';
            $paidAt = $isDirectPaid ? now() : null;

            // Generate payment reference code from gateway
            $paymentReference = $this->paymentGatewayService->generatePaymentReference($paymentMethod);

            $sale = Sale::create([
                'user_id' => $user->id,
                'customer_name' => $data['customer_name'] ?? $user->name,
                'shipping_address' => $data['shipping_address'] ?? $user->address,
                'sale_number' => $saleNumber,
                'total' => $total,
                'status' => $saleStatus,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'payment_reference' => $paymentReference,
                'paid_at' => $paidAt,
                'sold_at' => now(),
            ]);

            foreach ($itemsToCreate as $itemData) {
                $itemData['sale_id'] = $sale->id;
                SaleItem::create($itemData);
            }

            return $sale;
        });
    }

    /**
     * Cancel a sale transaction and restore inventory stock (Admin Only, Slide 5 & 8).
     *
     * @throws ValidationException|\Throwable
     */
    public function cancelSale(Sale $sale): array
    {
        return DB::transaction(function () use ($sale) {
            if ($sale->status === 'cancelled') {
                throw ValidationException::withMessages([
                    'error' => "Transaksi penjualan '{$sale->sale_number}' sudah dibatalkan sebelumnya.",
                ]);
            }

            // Restore physical stock for each item
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            $sale->update([
                'status' => 'cancelled',
                'payment_status' => 'refunded',
            ]);

            return [
                'success' => true,
                'message' => "Transaksi penjualan [{$sale->sale_number}] berhasil dibatalkan. Stok seluruh produk telah dipulihkan ke gudang dan status diubah menjadi Refunded.",
            ];
        });
    }
}
