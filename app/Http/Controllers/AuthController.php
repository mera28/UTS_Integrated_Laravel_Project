<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'customer' && ($pending = session()->pull('pending_cart_item'))) {
                $product = \App\Models\Product::find($pending['product_id']);
                if ($product && $product->stock >= $pending['quantity']) {
                    app(\App\Services\CartService::class)->addToCart(
                        $product,
                        (int) $pending['quantity'],
                        $pending['size'] ?? null,
                        $pending['color'] ?? null
                    );

                    if (! empty($pending['buy_now'])) {
                        return redirect()->route('checkout.index')
                            ->with('success', 'Selamat datang kembali, ' . $user->name . '! Pesanan Anda siap untuk di-checkout.');
                    }

                    return redirect()->route('cart.index')
                        ->with('success', 'Selamat datang kembali, ' . $user->name . '! Produk pilihan Anda telah otomatis dimasukkan ke keranjang.');
                }
            }

            if ($user->role === 'customer') {
                $intended = session()->get('url.intended');
                if ($intended && (str_contains($intended, '/dashboard') || str_contains($intended, '/portal'))) {
                    session()->forget('url.intended');
                }

                return redirect()->intended(route('shop.index'))
                    ->with('success', 'Selamat datang kembali di Zahiyyah\'s Fit, ' . $user->name . '!');
            }

            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '! Anda berhasil masuk.');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password tidak sesuai dengan catatan kami.',
        ])->onlyInput('email');
    }

    /**
     * Show customer registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle customer registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($pending = session()->pull('pending_cart_item')) {
            $product = \App\Models\Product::find($pending['product_id']);
            if ($product && $product->stock >= $pending['quantity']) {
                app(\App\Services\CartService::class)->addToCart(
                    $product,
                    (int) $pending['quantity'],
                    $pending['size'] ?? null,
                    $pending['color'] ?? null
                );

                if (! empty($pending['buy_now'])) {
                    return redirect()->route('checkout.index')
                        ->with('success', 'Akun berhasil dibuat! Pesanan Anda siap untuk di-checkout.');
                }

                return redirect()->route('cart.index')
                    ->with('success', 'Pendaftaran akun member berhasil! Produk pilihan Anda telah otomatis dimasukkan ke keranjang.');
            }
        }

        return redirect()->route('shop.index')->with('success', 'Pendaftaran akun member berhasil! Selamat berbelanja di Zahiyyah\'s Fit, ' . $user->name . '.');
    }

    /**
     * Log user out of the application.
     */
    public function logout(Request $request)
    {
        $role = Auth::user()?->role;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin' || $role === 'staff') {
            return redirect()->route('portal.login')->with('success', 'Anda telah berhasil keluar dari Portal Internal.');
        }

        return redirect()->route('shop.index')->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole(User $user)
    {
        if ($user->role === 'admin' || $user->role === 'staff') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('shop.index');
    }
}
