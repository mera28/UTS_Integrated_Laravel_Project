<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get paginated categories with product count and search filter.
     */
    public function getPaginatedCategories(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Category::withCount('products')->latest();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new category.
     *
     * @param array<string, mixed> $data
     */
    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param array<string, mixed> $data
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return $category;
    }

    /**
     * Safely delete a category if no products are associated.
     *
     * @return array{success: bool, message: string}
     */
    public function deleteCategory(Category $category): array
    {
        $productCount = $category->products()->count();

        if ($productCount > 0) {
            return [
                'success' => false,
                'message' => "Gagal menghapus! Kategori '{$category->name}' masih memiliki {$productCount} produk outfit terkait. Hapus atau pindahkan produk terlebih dahulu.",
            ];
        }

        $categoryName = $category->name;
        $category->delete();

        return [
            'success' => true,
            'message' => "Kategori '{$categoryName}' berhasil dihapus dari sistem.",
        ];
    }
}
