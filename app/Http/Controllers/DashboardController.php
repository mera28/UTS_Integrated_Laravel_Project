<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for Backoffice Analytics Dashboard & Low-Stock Alerts (Slide 6, 13, 18 PPT).
 */
class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Display backoffice analytics dashboard with live KPI metrics and trend cards.
     */
    public function index(): View
    {
        $metrics = $this->dashboardService->getKpiMetrics();
        $lowStockProducts = $this->dashboardService->getLowStockProducts(6);
        $topSellingProducts = $this->dashboardService->getTopSellingProducts(5);
        $paymentBreakdown = $this->dashboardService->getPaymentMethodBreakdown();
        $salesTrend = $this->dashboardService->getSalesTrend7Days();
        $recentTransactions = $this->dashboardService->getRecentTransactions(5);

        return view('dashboard.index', compact(
            'metrics',
            'lowStockProducts',
            'topSellingProducts',
            'paymentBreakdown',
            'salesTrend',
            'recentTransactions'
        ));
    }

    /**
     * Display dedicated Low Stock Alert monitoring list (Slide 13 PPT).
     */
    public function lowStock(): View
    {
        $lowStockProducts = $this->dashboardService->getLowStockProducts();
        $metrics = $this->dashboardService->getKpiMetrics();

        return view('dashboard.low_stock', compact('lowStockProducts', 'metrics'));
    }
}
