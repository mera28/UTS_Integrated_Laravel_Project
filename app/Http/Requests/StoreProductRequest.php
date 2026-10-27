<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'size' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'Kode SKU produk wajib diisi.',
            'sku.unique' => 'Kode SKU ini sudah terdaftar. Gunakan SKU unik.',
            'name.required' => 'Nama produk outfit wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'purchase_price.required' => 'Harga beli / modal HPP wajib diisi.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka valid.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.required' => 'Harga jual konsumen wajib diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa angka valid.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'minimum_stock.required' => 'Ambang batas minimum stok wajib diisi.',
            'minimum_stock.integer' => 'Batas minimum stok harus berupa bilangan bulat.',
        ];
    }
}
