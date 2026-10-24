<?php

namespace App\Services;

use App\Models\Sale;

class PaymentGatewayService
{
    /**
     * Generate unique payment reference string based on payment method.
     */
    public function generatePaymentReference(string $method, ?int $saleId = null): string
    {
        $uniqueNum = str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        $timestamp = date('His');

        return match ($method) {
            'qris' => 'QRIS-ZF-' . date('Ymd') . $uniqueNum,
            'va_bca' => '8271' . $uniqueNum,
            'va_mandiri' => '88708' . $uniqueNum,
            'va_bri' => '10234' . $uniqueNum,
            'va_bsi' => '7001' . $uniqueNum,
            'ewallet_shopeepay' => 'SPAY-' . strtoupper(bin2hex(random_bytes(4))),
            'ewallet_gopay' => 'GOPAY-' . strtoupper(bin2hex(random_bytes(4))),
            default => 'CASH-' . date('Ymd') . '-' . $timestamp,
        };
    }

    /**
     * Retrieve metadata information for supported payment methods.
     *
     * @return array{name: string, category: string, badge_class: string, icon: string, channel: string}
     */
    public function getPaymentMethodDetails(string $method): array
    {
        return match ($method) {
            'qris' => [
                'name' => 'QRIS Interaktif (Semua Bank & E-Wallet)',
                'category' => 'Instant QR',
                'badge_class' => 'bg-warning text-dark',
                'icon' => 'bi-qr-code-scan',
                'channel' => 'Gopay, ShopeePay, OVO, DANA, BCA Mobile, Livin, dll.',
            ],
            'va_bca' => [
                'name' => 'BCA Virtual Account',
                'category' => 'Virtual Account',
                'badge_class' => 'bg-primary text-white',
                'icon' => 'bi-bank',
                'channel' => 'Bank Central Asia (BCA)',
            ],
            'va_mandiri' => [
                'name' => 'Mandiri Virtual Account',
                'category' => 'Virtual Account',
                'badge_class' => 'bg-info text-dark',
                'icon' => 'bi-bank',
                'channel' => 'Bank Mandiri (Livin by Mandiri)',
            ],
            'va_bri' => [
                'name' => 'BRI Virtual Account (BRIVA)',
                'category' => 'Virtual Account',
                'badge_class' => 'bg-primary text-white',
                'icon' => 'bi-bank',
                'channel' => 'Bank Rakyat Indonesia (BRImo)',
            ],
            'va_bsi' => [
                'name' => 'BSI Virtual Account',
                'category' => 'Virtual Account',
                'badge_class' => 'bg-success text-white',
                'icon' => 'bi-bank',
                'channel' => 'Bank Syariah Indonesia (BSI Mobile)',
            ],
            'ewallet_shopeepay' => [
                'name' => 'ShopeePay',
                'category' => 'E-Wallet',
                'badge_class' => 'bg-danger text-white',
                'icon' => 'bi-wallet2',
                'channel' => 'Shopee / ShopeePay App',
            ],
            'ewallet_gopay' => [
                'name' => 'GoPay',
                'category' => 'E-Wallet',
                'badge_class' => 'bg-success text-white',
                'icon' => 'bi-wallet2',
                'channel' => 'Gojek / GoPay App',
            ],
            default => [
                'name' => 'Tunai / Cash (Kasir POS)',
                'category' => 'Direct Cash',
                'badge_class' => 'bg-secondary text-white',
                'icon' => 'bi-cash-stack',
                'channel' => 'Boutique Zahiyyah\'s Fit Counter',
            ],
        };
    }

    /**
     * Get step-by-step payment instructions for customer simulation.
     *
     * @return array<int, string>
     */
    public function getPaymentInstructions(string $method): array
    {
        return match ($method) {
            'qris' => [
                'Buka aplikasi mobile banking atau e-wallet pilihan Anda (BCA Mobile, Livin, GoPay, ShopeePay, OVO, DANA).',
                'Pilih menu "Scan QR" / "QRIS" pada aplikasi Anda.',
                'Arahkan kamera ke Kode QRIS interaktif yang tertera di layar.',
                'Periksa nama merchant: "Zahiyyah\'s Fit Official Store" dan pastikan nominal tagihan sesuai.',
                'Masukkan PIN transaksi Anda untuk menyelesaikan pembayaran secara instan.',
            ],
            'va_bca' => [
                'Buka aplikasi BCA Mobile / myBCA atau kunjungi ATM BCA terdekat.',
                'Pilih menu "m-Transfer" > "BCA Virtual Account".',
                'Masukkan nomor Virtual Account BCA resmi Zahiyyah\'s Fit yang tertera di atas.',
                'Periksa rincian pesanan dan total pembayaran pakaian Anda.',
                'Masukkan PIN m-BCA untuk konfirmasi pembayaran.',
            ],
            'va_mandiri' => [
                'Buka aplikasi Livin by Mandiri.',
                'Pilih menu "Bayar" > cari penyedia jasa "Zahiyyah\'s Fit".',
                'Masukkan nomor Virtual Account Mandiri yang tertera di halaman ini.',
                'Pastikan nominal total tagihan busana sudah sesuai.',
                'Konfirmasi pembayaran dan masukkan PIN Livin Mandiri Anda.',
            ],
            'va_bri', 'va_bsi' => [
                'Buka aplikasi Mobile Banking (BRImo / BSI Mobile).',
                'Pilih menu "Pembayaran" > "Virtual Account".',
                'Salin dan tempel nomor Virtual Account yang tertera di atas.',
                'Pastikan nama tujuan transaksi adalah Zahiyyah\'s Fit.',
                'Selesaikan pembayaran dengan memasukkan PIN.',
            ],
            'ewallet_shopeepay', 'ewallet_gopay' => [
                'Buka aplikasi E-Wallet Anda di smartphone.',
                'Pastikan saldo e-wallet Anda mencukupi total belanja outfit.',
                'Konfirmasikan transaksi dan masukkan PIN keamanan.',
                'Sistem akan otomatis memverifikasi pelunasan dalam hitungan detik.',
            ],
            default => [
                'Lakukan pembayaran tunai langsung di kasir butik Zahiyyah\'s Fit.',
                'Staf kasir akan mencetak struk bukti pembayaran sah.',
            ],
        };
    }

    /**
     * Confirm simulation payment successfully.
     *
     * @return array{success: bool, message: string}
     */
    public function confirmPayment(Sale $sale): array
    {
        if ($sale->payment_status === 'paid') {
            return [
                'success' => true,
                'message' => 'Pesanan ini sudah dinyatakan lunas sebelumnya.',
            ];
        }

        $sale->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => "Pembayaran sebesar Rp " . number_format($sale->total, 0, ',', '.') . " untuk pesanan [{$sale->sale_number}] berhasil diverifikasi Lunas!",
        ];
    }
}
