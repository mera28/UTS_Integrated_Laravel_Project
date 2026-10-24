<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for Dashboard Real-time Analytics & Aggregations (Slide 6, 13, 18 PPT).
 */
class DashboardService
{
    /**
     * Retrieve high-level KPI summary cards data.
     *
     * @return array<string, mixed>
     */
    public function getKpiMetrics(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Master Counts
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock');
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 2. Sales Metrics (Slide 18)
        $todaySales = (float) Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->sum('total');

        $todaySalesCount = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->count();

        $monthSales = (float) Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $monthSalesCount = Sale::where('status', 'completed')
            ->whereBetween('sold_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 3. Purchase / Stock-In Metrics
        $monthPurchases = (float) Purchase::where('status', 'completed')
            ->whereBetween('purchased_at', [$startOfMonth, $endOfMonth])
            ->sum('total');

        $monthPurchasesCount = Purchase::where('status', 'completed')
            ->whereBetween('purchased_at', [$startOfMonth, $endOfMonth])
            ->count();

        // 4. Low stock count
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        return [
            'total_products' => $totalProducts,
            'total_stock' => $totalStock,
            'total_categories' => $totalCategories,
            'total_suppliers' => $totalSuppliers,
            'total_customers' => $totalCustomers,
            'today_sales' => $todaySales,
            'today_sales_count' => $todaySalesCount,
            'month_sales' => $monthSales,
            'month_sales_count' => $monthSalesCount,
            'month_purchases' => $monthPurchases,
            'month_purchases_count' => $monthPurchasesCount,
            'low_stock_count' => $lowStockCount,
            'net_profit_estimate' => $monthSales - $monthPurchases,
        ];
    }

    /**
     * Retrieve list of products that reached or fell below minimum stock (Slide 13 PPT).
     *
     * @param int|null $limit
     * @return Collection<int, Product>
     */
    public function getLowStockProducts(?int $limit = null): Collection
    {
        $query = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->orderBy('name', 'asc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Retrieve top selling outfit products based on quantity sold in completed sales.
     *
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getTopSellingProducts(int $limit = 5): array
    {
        $records = SaleItem::select(
                'sale_items.product_id',
                DB::raw('SUM(sale_items.quantity) as total_qty_sold'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.status', 'completed')
            ->groupBy('sale_items.product_id')
            ->orderByDesc('total_qty_sold')
            ->limit($limit)
            ->with(['product.category'])
            ->get();

        $result = [];
        foreach ($records as $item) {
            if ($item->product) {
                $result[] = [
                    'product' => $item->product,
                    'total_sold' => (int) $item->total_qty_sold,
                    'total_revenue' => (float) $item->total_revenue,
                ];
            }
        }

        return $result;
    }

    /**
     * Retrieve payment gateway method breakdown and popularity.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getPaymentMethodBreakdown(): array
    {
        $rawRecords = Sale::select(
                'payment_method',
                DB::raw('COUNT(id) as total_tx'),
                DB::raw('SUM(total) as total_amount')
            )
            ->where('status', 'completed')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get();

        $totalCompletedRevenue = (float) Sale::where('status', 'completed')->sum('total');

        $methodLabels = [
            'cash' => ['label' => 'Tunai Kasir (Cash POS)', 'icon' => 'bi-cash-coin', 'color' => '#198754'],
            'qris' => ['label' => 'QRIS (All Payment)', 'icon' => 'bi-qr-code-scan', 'color' => '#0d6efd'],
            'va_bca' => ['label' => 'BCA Virtual Account', 'icon' => 'bi-bank', 'color' => '#005baa'],
            'va_mandiri' => ['label' => 'Mandiri Virtual Account', 'icon' => 'bi-bank', 'color' => '#002d62'],
            'va_bri' => ['label' => 'BRI Virtual Account', 'icon' => 'bi-bank', 'color' => '#00529c'],
            'va_bsi' => ['label' => 'BSI Virtual Account', 'icon' => 'bi-bank', 'color' => '#00a39d'],
            'ewallet_shopeepay' => ['label' => 'ShopeePay Instant', 'icon' => 'bi-wallet2', 'color' => '#ee4d2d'],
            'ewallet_gopay' => ['label' => 'GoPay Instant', 'icon' => 'bi-wallet2', 'color' => '#00aed6'],
        ];

        $breakdown = [];
        foreach ($rawRecords as $record) {
            $method = $record->payment_method ?? 'cash';
            $meta = $methodLabels[$method] ?? ['label' => ucfirst($method), 'icon' => 'bi-credit-card', 'color' => '#6c757d'];
            $amount = (float) $record->total_amount;
            $percentage = $totalCompletedRevenue > 0 ? round(($amount / $totalCompletedRevenue) * 100, 1) : 0;

            $breakdown[$method] = [
                'method' => $method,
                'label' => $meta['label'],
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'total_transactions' => (int) $record->total_tx,
                'total_amount' => $amount,
                'percentage' => $percentage,
            ];
        }

        return $breakdown;
    }

    /**
     * Retrieve 7-day daily sales trend.
     *
     * @return array<int, array{date: string, formatted_date: string, total_amount: float, total_orders: int}>
     */
    public function getSalesTrend7Days(): array
    {
        $trend = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();

            $daySales = (float) Sale::where('status', 'completed')
                ->whereDate('sold_at', $dateStr)
                ->sum('total');

            $dayOrders = Sale::where('status', 'completed')
                ->whereDate('sold_at', $dateStr)
                ->count();

            $trend[] = [
                'date' => $dateStr,
                'formatted_date' => $date->isoFormat('D MMM'),
                'day_name' => $date->isoFormat('dddd'),
                'total_amount' => $daySales,
                'total_orders' => $dayOrders,
            ];
        }

        return $trend;
    }

    /**
     * Retrieve recent transactions for both sales and purchases.
     *
     * @param int $limit
     * @return array{recent_sales: Collection<int, Sale>, recent_purchases: Collection<int, Purchase>}
     */
    public function getRecentTransactions(int $limit = 5): array
    {
        return [
            'recent_sales' => Sale::with(['user', 'items.product'])
                ->latest('sold_at')
                ->take($limit)
                ->get(),
            'recent_purchases' => Purchase::with(['supplier', 'items.product'])
                ->latest('purchased_at')
                ->take($limit)
                ->get(),
        ];
    }
}
