<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isStaff());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
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
            'supplier_id.required' => 'Supplier / vendor pemasok wajib dipilih.',
            'supplier_id.exists' => 'Data supplier yang dipilih tidak valid.',
            'purchased_at.date' => 'Format tanggal pembelian tidak valid.',
            'items.required' => 'Minimal harus menambahkan 1 baris item produk pakaian.',
            'items.min' => 'Minimal harus menambahkan 1 baris item produk pakaian.',
            'items.*.product_id.required' => 'Produk outfit pada setiap baris wajib dipilih.',
            'items.*.product_id.distinct' => 'Terdapat produk ganda pada baris item. Gabungkan kuantitas pada satu baris.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ditemukan di katalog.',
            'items.*.quantity.required' => 'Kuantitas pembelian wajib diisi.',
            'items.*.quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'items.*.quantity.min' => 'Kuantitas pembelian minimal 1 pcs (Slide 8).',
            'items.*.unit_cost.required' => 'Harga modal beli per unit wajib diisi.',
            'items.*.unit_cost.numeric' => 'Harga modal beli harus berupa angka.',
            'items.*.unit_cost.min' => 'Harga beli modal tidak boleh negatif.',
        ];
    }
}
