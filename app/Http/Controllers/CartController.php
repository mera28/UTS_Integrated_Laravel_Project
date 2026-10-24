<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display shopping cart view.
     */
    public function index()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getTotals();

        return view('shop.cart', compact('cart', 'totals'));
    }

    /**
     * Add product to shopping cart.
     */
    public function add(Request $request, Product $product)
    {
        $quantity = (int) ($request->quantity ?? 1);
        $size = $request->input('size', $request->input('selected_size'));
        $color = $request->input('color', $request->input('selected_color'));

        if (! \Illuminate\Support\Facades\Auth::check()) {
            session()->put('pending_cart_item', [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color,
                'buy_now' => $request->boolean('buy_now'),
            ]);

            return redirect()->route('login')->with('info', 'Silakan masuk atau daftar dengan Akun Google untuk memesan outfit ' . $product->name . '.');
        }

        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'selected_size' => ['nullable', 'string', 'max:50'],
            'selected_color' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $result = $this->cartService->addToCart(
                $product,
                $quantity,
                $size,
                $color
            );

            if ($request->boolean('buy_now')) {
                return redirect()->route('checkout.index');
            }

            return back()->with('success', $result['message']);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Update quantity of an item in cart.
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $result = $this->cartService->updateQuantity($request->cart_key, (int) $request->quantity);
            return redirect()->route('cart.index')->with('success', $result['message']);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    /**
     * Remove an item from cart.
     */
    public function remove(string $cartKey)
    {
        $this->cartService->removeFromCart($cartKey);
        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Empty entire cart.
     */
    public function clear()
    {
        $this->cartService->clearCart();
        return redirect()->route('cart.index')->with('success', 'Keranjang belanja telah dikosongkan.');
    }
}
