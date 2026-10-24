<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\GoogleAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for Interactive Customer Google Sign-In / OAuth Flow.
 */
class GoogleAuthController extends Controller
{
    public function __construct(
        protected GoogleAuthService $googleAuthService,
        protected CartService $cartService
    ) {}

    /**
     * Display Google Account Chooser modal/page ("Choose an account to continue to Zahiyyah's Fit").
     */
    public function redirectToGoogle(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('shop.index');
        }

        $presetAccounts = $this->googleAuthService->getPresetAccounts();

        return view('auth.google_choose', compact('presetAccounts'));
    }

    /**
     * Handle callback after customer selects or inputs their Google account.
     */
    public function handleGoogleCallback(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Alamat email Google wajib diisi.',
            'email.email' => 'Format email Google tidak valid.',
            'name.required' => 'Nama lengkap akun Google wajib diisi.',
        ]);

        $user = $this->googleAuthService->loginOrCreateGoogleUser($validated);
        $request->session()->regenerate();

        // Check if there was a pending cart item when guest clicked add to cart / buy now
        if ($pending = session()->pull('pending_cart_item')) {
            $product = Product::find($pending['product_id']);
            if ($product && $product->stock >= $pending['quantity']) {
                $this->cartService->addToCart(
                    $product,
                    (int) $pending['quantity'],
                    $pending['size'] ?? null,
                    $pending['color'] ?? null
                );

                if (! empty($pending['buy_now'])) {
                    return redirect()->route('checkout.index')
                        ->with('success', "Halo {$user->name}! Anda berhasil masuk dengan Akun Google. Silakan lanjutkan pengiriman pesanan Anda.");
                }

                return redirect()->route('cart.index')
                    ->with('success', "Halo {$user->name}! Anda berhasil masuk dengan Google. Outfit {$product->name} telah otomatis dimasukkan ke keranjang.");
            }
        }

        return redirect()->intended(route('shop.index'))
            ->with('success', "Selamat datang, {$user->name}! Anda berhasil masuk menggunakan Akun Google ({$user->email}).");
    }
}
