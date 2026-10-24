<?php

namespace App\Support;

use Carbon\Carbon;

class FormatHelper
{
    /**
     * Format number into Indonesian Rupiah currency format.
     */
    public static function rupiah(float|int|string|null $amount): string
    {
        $num = (float) ($amount ?? 0);
        return 'Rp ' . number_format($num, 0, ',', '.');
    }

    /**
     * Format date into Indonesian readable format.
     */
    public static function indonesianDate(mixed $date, bool $withTime = false): string
    {
        if (! $date) {
            return '-';
        }

        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $day = $carbon->format('d');
        $month = $months[(int) $carbon->format('m')];
        $year = $carbon->format('Y');

        if ($withTime) {
            return "{$day} {$month} {$year}, " . $carbon->format('H:i') . ' WIB';
        }

        return "{$day} {$month} {$year}";
    }

    /**
     * Render HTML badge string for stock status.
     */
    public static function stockBadge(int $stock, int $minimumStock = 5): string
    {
        if ($stock <= 0) {
            return '<span class="badge badge-stock-empty px-2 py-1"><i class="bi bi-x-circle me-1"></i> Habis (0)</span>';
        }

        if ($stock <= $minimumStock) {
            return '<span class="badge badge-stock-low px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> Menipis (' . $stock . ' pcs)</span>';
        }

        return '<span class="badge badge-stock-normal px-2 py-1"><i class="bi bi-check-circle me-1"></i> Tersedia (' . $stock . ' pcs)</span>';
    }

    /**
     * Calculate nominal profit and percentage profit margin.
     *
     * @return array{profit: float, percentage: float}
     */
    public static function calculateMargin(float $purchasePrice, float $sellingPrice): array
    {
        $profit = $sellingPrice - $purchasePrice;
        $percentage = $purchasePrice > 0 ? ($profit / $purchasePrice) * 100 : 0;

        return [
            'profit' => $profit,
            'percentage' => round($percentage, 2),
        ];
    }

    /**
     * Generate unique SKU code with category code.
     */
    public static function generateSku(string $categorySlug, ?int $nextId = null): string
    {
        $prefix = match ($categorySlug) {
            'gamis-maxy-dress' => 'ZF-GMS',
            'blouse-tunik' => 'ZF-BLS',
            'celana-kulot' => 'ZF-CLN',
            'rok-pleated-skirt' => 'ZF-ROK',
            'hijab-pashmina' => 'ZF-HJB',
            'outerwear-cardigan' => 'ZF-OUT',
            default => 'ZF-OUTFIT',
        };

        $sequence = str_pad((string) ($nextId ?? rand(100, 999)), 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$sequence}";
    }

    /**
     * Generate sequential/timestamped invoice sale number.
     */
    public static function generateSaleNumber(): string
    {
        return 'INV-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }

    /**
     * Generate sequential/timestamped purchase order number.
     */
    public static function generatePurchaseNumber(): string
    {
        return 'PO-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(2)));
    }
}
