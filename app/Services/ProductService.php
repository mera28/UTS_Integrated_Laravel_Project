<?php

namespace App\Services;

use App\Models\Product;
use App\Support\FormatHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Retrieve products with search, category filtering, and stock status filters.
     *
     * @param array{search?: string|null, category_id?: int|string|null, stock_status?: string|null} $filters
     */
    public function getFilteredProducts(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Product::with('category')->latest();

        // Search filter (SKU, name, color)
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%");
            });
        }

        // Category filter
        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Stock status filter
        if (! empty($filters['stock_status'])) {
            match ($filters['stock_status']) {
                'low' => $query->whereColumn('stock', '<=', 'minimum_stock')->where('stock', '>', 0),
                'empty' => $query->where('stock', '<=', 0),
                'available' => $query->whereColumn('stock', '>', 'minimum_stock'),
                default => null,
            };
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new outfit product.
     *
     * @param array<string, mixed> $data
     */
    public function createProduct(array $data): Product
    {
        $data['stock'] = $data['stock'] ?? 0;
        return Product::create($data);
    }

    /**
     * Update an existing product.
     *
     * @param array<string, mixed> $data
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    /**
     * Soft delete product according to business rules (Slide 8).
     *
     * @return array{success: bool, message: string}
     */
    public function deleteProduct(Product $product): array
    {
        $hasSales = $product->saleItems()->count();
        $hasPurchases = $product->purchaseItems()->count();

        $productName = $product->name;
        $productSku = $product->sku;

        // Perform Soft Delete
        $product->delete();

        if ($hasSales > 0 || $hasPurchases > 0) {
            return [
                'success' => true,
                'message' => "Produk [{$productSku}] '{$productName}' telah di-arsip (Soft Delete). Riwayat nota penjualan dan transaksi pengadaan tetap aman terlindungi.",
            ];
        }

        return [
            'success' => true,
            'message' => "Produk [{$productSku}] '{$productName}' berhasil dihapus.",
        ];
    }

    /**
     * Calculate financial and inventory metrics for a single product.
     *
     * @return array{margin: array{profit: float, percentage: float}, sales_count: int, purchases_count: int}
     */
    public function getProductMetrics(Product $product): array
    {
        $margin = FormatHelper::calculateMargin((float) $product->purchase_price, (float) $product->selling_price);
        $salesCount = $product->saleItems()->sum('quantity');
        $purchasesCount = $product->purchaseItems()->sum('quantity');

        return [
            'margin' => $margin,
            'sales_count' => (int) $salesCount,
            'purchases_count' => (int) $purchasesCount,
        ];
    }
}
