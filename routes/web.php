<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to shop storefront
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Authentication Routes (Customer & General)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google Sign-In Simulation Routes (Customer OAuth)
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::post('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Internal Admin & Staff Portal Routes (Always accessible, independent of customer session)
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [PortalAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PortalAuthController::class, 'login']);
    Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout')->middleware('auth');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// E-Commerce Storefront Routes (Customer & Public)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/products/{product}', [ShopController::class, 'show'])->name('products.show')->whereNumber('product');
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::get('/orders', [ShopController::class, 'orders'])->name('orders')->middleware('auth');
});

// Shopping Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add')->whereNumber('product');
    Route::patch('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::delete('/{key}', [CartController::class, 'remove'])->name('remove');
});

// Customer Authenticated Checkout & Payment Gateway Simulation Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CustomerOrderController::class, 'checkoutView'])->name('checkout.index');
    Route::post('/checkout', [CustomerOrderController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/orders/{sale}', [CustomerOrderController::class, 'orderDetail'])->name('orders.show')->whereNumber('sale');
    Route::get('/orders/{sale}/payment', [CustomerOrderController::class, 'paymentView'])->name('orders.payment')->whereNumber('sale');
    Route::post('/orders/{sale}/simulate-pay', [CustomerOrderController::class, 'simulatePaymentSuccess'])->name('orders.simulate-pay')->whereNumber('sale');
});

// Backoffice Protected Routes (Admin & Staff)
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products: Read-only access for staff & admin
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->whereNumber('product');

    // Purchases (Stock-In): Accessible by Admin & Staff
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show')->whereNumber('purchase');

    // Sales: POS Cashier & Orders List
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show')->whereNumber('sale');

    // Low-Stock Monitoring & Real-time Alerts (Slide 13 PPT)
    Route::get('/low-stock', [DashboardController::class, 'lowStock'])->name('dashboard.low-stock');

    // Financial & Transaction Reports (Slide 6, 18, 25 PPT)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
});

// Backoffice Admin-Only Routes (Full Master CRUD & Cancellation)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Products: Create, Edit, Update, Delete (Admin Only)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->whereNumber('product');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->whereNumber('product');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->whereNumber('product');

    // Master Categories, Suppliers, Users
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);

    // Cancellations & Stock Reversals (Admin Only)
    Route::patch('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel')->whereNumber('purchase');
    Route::patch('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel')->whereNumber('sale');
});
