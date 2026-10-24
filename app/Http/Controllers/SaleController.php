<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\PaymentGatewayService;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected PaymentGatewayService $paymentGatewayService
    ) {}

    /**
     * Display a listing of sales transactions.
     */
    public function index(Request $request)
    {
        $sales = $this->saleService->getFilteredSales([
            'search' => $request->search,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ], 10);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the POS (Point of Sale) cashier form.
     */
    public function create()
    {
        $products = Product::with('category')->where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created POS cashier sale in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_pos'] = true;

            $sale = $this->saleService->createSale($data, $request->user());

            return redirect()->route('sales.show', $sale)
                ->with('success', "Transaksi kasir [{$sale->sale_number}] berhasil disimpan dan stok produk telah diperbarui.");
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses transaksi kasir: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sale invoice details.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product.category']);
        $paymentDetails = $this->paymentGatewayService->getPaymentMethodDetails($sale->payment_method ?? 'cash');

        return view('sales.show', compact('sale', 'paymentDetails'));
    }

    /**
     * Cancel the sale transaction and restore inventory stock (Admin Only).
     */
    public function cancel(Sale $sale)
    {
        try {
            $result = $this->saleService->cancelSale($sale);
            return redirect()->route('sales.show', $sale)->with('success', $result['message']);
        } catch (ValidationException $e) {
            $errorMsg = $e->validator->errors()->first('error') ?? 'Gagal membatalkan transaksi.';
            return redirect()->route('sales.show', $sale)->with('error', $errorMsg);
        } catch (\Exception $e) {
            return redirect()->route('sales.show', $sale)->with('error', 'Kesalahan sistem: ' . $e->getMessage());
        }
    }
}
