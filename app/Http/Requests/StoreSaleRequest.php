<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'customer_name' => ['nullable', 'string', 'max:150'],
            'payment_method' => ['required', 'string', 'in:cash,qris,va_bca,va_mandiri,va_bri,va_bsi,ewallet_shopeepay,ewallet_gopay'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.selected_size' => ['nullable', 'string', 'max:50'],
            'items.*.selected_color' => ['nullable', 'string', 'max:50'],
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
            'payment_method.required' => 'Metode pembayaran kasir POS wajib dipilih.',
            'payment_method.in' => 'Metode pembayaran yang dipilih tidak valid.',
            'items.required' => 'Minimal harus menambahkan 1 item produk outfit ke kasir.',
            'items.min' => 'Minimal harus menambahkan 1 item produk outfit ke kasir.',
            'items.*.product_id.required' => 'Produk outfit wajib dipilih.',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak ditemukan di katalog.',
            'items.*.quantity.required' => 'Kuantitas penjualan wajib diisi.',
            'items.*.quantity.min' => 'Kuantitas penjualan minimal 1 pcs (Slide 8).',
        ];
    }
}
