<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierService
{
    /**
     * Get paginated suppliers with purchase count and search filter.
     */
    public function getPaginatedSuppliers(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Supplier::withCount('purchases')->latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new supplier.
     *
     * @param array<string, mixed> $data
     */
    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Update an existing supplier.
     *
     * @param array<string, mixed> $data
     */
    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier;
    }

    /**
     * Safely delete a supplier if no purchases exist.
     *
     * @return array{success: bool, message: string}
     */
    public function deleteSupplier(Supplier $supplier): array
    {
        $purchaseCount = $supplier->purchases()->count();

        if ($purchaseCount > 0) {
            $displayName = $supplier->company_name ?? $supplier->name;
            return [
                'success' => false,
                'message' => "Gagal menghapus! Supplier '{$displayName}' memiliki {$purchaseCount} riwayat transaksi pengadaan (stock-in). Data supplier dilindungi untuk arsip akuntansi.",
            ];
        }

        $supplierName = $supplier->company_name ?? $supplier->name;
        $supplier->delete();

        return [
            'success' => true,
            'message' => "Data supplier '{$supplierName}' berhasil dihapus.",
        ];
    }
}
