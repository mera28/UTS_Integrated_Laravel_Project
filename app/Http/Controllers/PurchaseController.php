<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $purchaseService
    ) {}

    /**
     * Display a listing of purchase stock-in transactions.
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $purchases = $this->purchaseService->getFilteredPurchases([
            'search' => $request->search,
            'supplier_id' => $request->supplier_id,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ], 10);

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show the form for creating a new stock-in purchase.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $products = Product::with('category')->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        try {
            $purchase = $this->purchaseService->createPurchase(
                $request->validated(),
                $request->user()->id
            );

            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Transaksi pengadaan [{$purchase->purchase_number}] berhasil disimpan dan stok produk telah ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase details and print view.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.category']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Cancel the purchase transaction and reverse stock (Admin Only).
     */
    public function cancel(Purchase $purchase)
    {
        try {
            $result = $this->purchaseService->cancelPurchase($purchase);
            return redirect()->route('purchases.show', $purchase)->with('success', $result['message']);
        } catch (ValidationException $e) {
            $errorMsg = $e->validator->errors()->first('error') ?? 'Gagal membatalkan transaksi.';
            return redirect()->route('purchases.show', $purchase)->with('error', $errorMsg);
        } catch (\Exception $e) {
            return redirect()->route('purchases.show', $purchase)->with('error', 'Kesalahan sistem: ' . $e->getMessage());
        }
    }
}
