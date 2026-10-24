<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display backoffice dashboard.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->get();
        $totalSales = Sale::where('payment_status', 'paid')->sum('total');
        $totalPurchases = Purchase::where('status', 'completed')->sum('total');

        $recentSales = Sale::with(['user', 'items.product'])->latest('sold_at')->take(5)->get();
        $recentPurchases = Purchase::with(['supplier', 'items.product'])->latest('purchased_at')->take(5)->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'totalSales',
            'totalPurchases',
            'recentSales',
            'recentPurchases'
        ));
    }
}
