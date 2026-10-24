<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for Financial, Inventory, and Transaction Reports (Slide 6, 18, 25 PPT).
 */
class ReportService
{
    /**
     * Retrieve aggregated financial summary metrics based on filters.
     *
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function getFinancialSummary(array $filters = []): array
    {
        $salesQuery = Sale::query();
        $purchasesQuery = Purchase::query();

        // Apply date filters
        if (! empty($filters['start_date'])) {
            $salesQuery->whereDate('sold_at', '>=', $filters['start_date']);
            $purchasesQuery->whereDate('purchased_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $salesQuery->whereDate('sold_at', '<=', $filters['end_date']);
            $purchasesQuery->whereDate('purchased_at', '<=', $filters['end_date']);
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $salesQuery->where('status', $filters['status']);
            $purchasesQuery->where('status', $filters['status']);
        }

        // Apply payment method filter (affects sales)
        if (! empty($filters['payment_method'])) {
            $salesQuery->where('payment_method', $filters['payment_method']);
        }

        // Calculate Sales Totals
        $completedSalesQuery = (clone $salesQuery)->where('status', 'completed');
        $totalSalesRevenue = (float) $completedSalesQuery->sum('total');
        $totalSalesCount = $completedSalesQuery->count();

        // Calculate Purchases Totals
        $completedPurchasesQuery = (clone $purchasesQuery)->where('status', 'completed');
        $totalPurchasesExpense = (float) $completedPurchasesQuery->sum('total');
        $totalPurchasesCount = $completedPurchasesQuery->count();

        // Net Margin (Revenue - Expense)
        $netMargin = $totalSalesRevenue - $totalPurchasesExpense;
        $profitMarginPercentage = $totalSalesRevenue > 0
            ? round(($netMargin / $totalSalesRevenue) * 100, 1)
            : 0;

        // Total Items Sold and Purchased in range
        $totalUnitsSold = (int) SaleItem::whereHas('sale', function (Builder $q) use ($filters) {
            $q->where('status', 'completed');
            if (! empty($filters['start_date'])) {
                $q->whereDate('sold_at', '>=', $filters['start_date']);
            }
            if (! empty($filters['end_date'])) {
                $q->whereDate('sold_at', '<=', $filters['end_date']);
            }
        })->sum('quantity');

        $totalUnitsPurchased = (int) PurchaseItem::whereHas('purchase', function (Builder $q) use ($filters) {
            $q->where('status', 'completed');
            if (! empty($filters['start_date'])) {
                $q->whereDate('purchased_at', '>=', $filters['start_date']);
            }
            if (! empty($filters['end_date'])) {
                $q->whereDate('purchased_at', '<=', $filters['end_date']);
            }
        })->sum('quantity');

        return [
            'total_sales_revenue' => $totalSalesRevenue,
            'total_sales_count' => $totalSalesCount,
            'total_purchases_expense' => $totalPurchasesExpense,
            'total_purchases_count' => $totalPurchasesCount,
            'net_margin' => $netMargin,
            'profit_margin_percentage' => $profitMarginPercentage,
            'total_units_sold' => $totalUnitsSold,
            'total_units_purchased' => $totalUnitsPurchased,
        ];
    }

    /**
     * Retrieve paginated sales transactions matching report criteria.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getSalesReport(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Sale::with(['user', 'items.product'])
            ->latest('sold_at');

        $this->applySaleFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Retrieve paginated purchase transactions matching report criteria.
     *
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPurchasesReport(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Purchase::with(['supplier', 'user', 'items.product'])
            ->latest('purchased_at');

        $this->applyPurchaseFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Retrieve stock valuation and inventory health report.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getStockReport(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('category')
            ->orderBy('stock', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Calculate current total stock valuation in inventory.
     *
     * @return array<string, float|int>
     */
    public function getStockValuationSummary(): array
    {
        $totalUnits = (int) Product::sum('stock');
        $totalCostValue = (float) Product::select(DB::raw('SUM(stock * purchase_price) as cost_val'))->value('cost_val');
        $totalRetailValue = (float) Product::select(DB::raw('SUM(stock * selling_price) as retail_val'))->value('retail_val');
        $potentialProfit = $totalRetailValue - $totalCostValue;

        return [
            'total_units' => $totalUnits,
            'total_cost_value' => $totalCostValue,
            'total_retail_value' => $totalRetailValue,
            'potential_profit' => $potentialProfit,
        ];
    }

    /**
     * Retrieve all records without pagination for printable view.
     *
     * @param array<string, mixed> $filters
     * @return array<string, mixed>
     */
    public function getPrintData(array $filters = []): array
    {
        $type = $filters['report_type'] ?? 'sales';
        $summary = $this->getFinancialSummary($filters);
        $stockSummary = $this->getStockValuationSummary();

        $records = [];
        if ($type === 'sales') {
            $query = Sale::with(['user', 'items.product'])->latest('sold_at');
            $this->applySaleFilters($query, $filters);
            $records = $query->get();
        } elseif ($type === 'purchases') {
            $query = Purchase::with(['supplier', 'user', 'items.product'])->latest('purchased_at');
            $this->applyPurchaseFilters($query, $filters);
            $records = $query->get();
        } else {
            $records = Product::with('category')->orderBy('stock', 'asc')->get();
        }

        return [
            'type' => $type,
            'filters' => $filters,
            'summary' => $summary,
            'stock_summary' => $stockSummary,
            'records' => $records,
            'printed_at' => Carbon::now()->isoFormat('D MMMM Y, HH:mm') . ' WIB',
        ];
    }

    /**
     * Helper to apply common filters to Sale query.
     */
    private function applySaleFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['start_date'])) {
            $query->whereDate('sold_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('sold_at', '<=', $filters['end_date']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }
    }

    /**
     * Helper to apply common filters to Purchase query.
     */
    private function applyPurchaseFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['start_date'])) {
            $query->whereDate('purchased_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $query->whereDate('purchased_at', '<=', $filters['end_date']);
        }
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
