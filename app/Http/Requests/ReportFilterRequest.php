<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating Financial and Transaction Report Filters.
 */
class ReportFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin and Staff can view and generate reports
        return $this->user() && in_array($this->user()->role, ['admin', 'staff'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'report_type' => ['nullable', 'string', 'in:all,sales,purchases,stock'],
            'status' => ['nullable', 'string', 'in:completed,pending,cancelled'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'start_date' => 'Tanggal Awal',
            'end_date' => 'Tanggal Akhir',
            'report_type' => 'Jenis Laporan',
            'status' => 'Status Transaksi',
            'payment_method' => 'Metode Pembayaran',
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'end_date.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            'report_type.in' => 'Pilihan jenis laporan tidak valid.',
            'status.in' => 'Status transaksi tidak valid.',
        ];
    }
}
