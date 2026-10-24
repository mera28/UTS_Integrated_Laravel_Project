<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to shop storefront
Route::get('/', function () {
    return redirect()->route('shop.index');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// E-Commerce Storefront Routes (Customer & Public)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/cart', [ShopController::class, 'cart'])->name('cart');
    Route::get('/orders', [ShopController::class, 'orders'])->name('orders')->middleware('auth');
});

// Backoffice Routes (Admin & Staff)
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
});

// Backoffice Admin-Only Routes (Full Master CRUD)
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

    // Cancel purchase & reverse stock (Admin Only)
    Route::patch('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel')->whereNumber('purchase');
});
