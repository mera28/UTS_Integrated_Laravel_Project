<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $products = $this->productService->getFilteredProducts([
            'search' => $request->search,
            'category_id' => $request->category_id,
            'stock_status' => $request->stock_status,
        ], 10);

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display product details and profit margin.
     */
    public function show(Product $product)
    {
        $product->load('category');
        $metrics = $this->productService->getProductMetrics($product);

        return view('products.show', compact('product', 'metrics'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());

        return redirect()->route('products.index')
            ->with('success', "Produk outfit [{$product->sku}] '{$product->name}' berhasil ditambahkan ke inventori.");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->updateProduct($product, $request->validated());

        return redirect()->route('products.index')
            ->with('success', "Data produk [{$product->sku}] '{$product->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified product from storage (Soft Delete).
     */
    public function destroy(Product $product)
    {
        $result = $this->productService->deleteProduct($product);

        return redirect()->route('products.index')
            ->with('success', $result['message']);
    }
}
