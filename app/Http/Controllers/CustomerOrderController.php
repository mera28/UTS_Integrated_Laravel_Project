<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Sale;
use App\Services\CartService;
use App\Services\PaymentGatewayService;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomerOrderController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected SaleService $saleService,
        protected PaymentGatewayService $paymentGatewayService
    ) {}

    /**
     * Show customer checkout page.
     */
    public function checkoutView()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getTotals();

        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih pakaian favorit Anda terlebih dahulu.');
        }

        $user = Auth::user();
        return view('shop.checkout', compact('cart', 'totals', 'user'));
    }

    /**
     * Process checkout submission and redirect to payment gateway simulation.
     */
    public function processCheckout(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('shop.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'selected_size' => $item['size'],
                'selected_color' => $item['color'],
            ];
        }

        try {
            $saleData = [
                'customer_name' => $request->customer_name,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'is_pos' => false,
                'items' => $items,
            ];

            $sale = $this->saleService->createSale($saleData, $request->user());

            // Clear shopping cart on successful checkout
            $this->cartService->clearCart();

            return redirect()->route('orders.payment', $sale)
                ->with('success', "Pesanan [{$sale->sale_number}] berhasil dibuat! Silakan selesaikan pembayaran melalui simulasi payment gateway.");
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses checkout pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Display interactive payment gateway simulation page (Midtrans/ShopeePay style).
     */
    public function paymentView(Sale $sale)
    {
        $this->authorizeCustomerSale($sale);

        $sale->load('items.product');
        $paymentDetails = $this->paymentGatewayService->getPaymentMethodDetails($sale->payment_method ?? 'qris');
        $instructions = $this->paymentGatewayService->getPaymentInstructions($sale->payment_method ?? 'qris');

        return view('shop.payment', compact('sale', 'paymentDetails', 'instructions'));
    }

    /**
     * Simulate successful payment from the payment gateway page.
     */
    public function simulatePaymentSuccess(Sale $sale)
    {
        $this->authorizeCustomerSale($sale);

        $result = $this->paymentGatewayService->confirmPayment($sale);

        return redirect()->route('orders.show', $sale)->with('success', $result['message']);
    }

    /**
     * Display order invoice details for customer.
     */
    public function orderDetail(Sale $sale)
    {
        $this->authorizeCustomerSale($sale);

        $sale->load(['items.product.category']);
        $paymentDetails = $this->paymentGatewayService->getPaymentMethodDetails($sale->payment_method ?? 'cash');

        return view('shop.order_detail', compact('sale', 'paymentDetails'));
    }

    /**
     * Authorize that the current user owns this sale or is admin/staff.
     */
    protected function authorizeCustomerSale(Sale $sale): void
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Silakan masuk akun terlebih dahulu.');
        }

        if ($sale->user_id !== $user->id && ! $user->isAdmin() && ! $user->isStaff()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke pesanan pelanggan lain.');
        }
    }
}
