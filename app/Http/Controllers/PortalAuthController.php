<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for Internal Admin & Staff Portal Authentication.
 */
class PortalAuthController extends Controller
{
    /**
     * Display portal login view for internal staff & admin.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin() || $user->isStaff()) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('shop.index');
        }

        return view('auth.portal_login');
    }

    /**
     * Handle portal authentication attempt.
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
            $user = Auth::user();

            // Strictly disallow customer role from accessing internal portal
            if (! in_array($user->role, ['admin', 'staff'], true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda terdaftar sebagai Pelanggan (Customer). Silakan masuk melalui halaman login pelanggan.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang di Portal Internal Zahiyyah\'s Fit, ' . $user->name . ' (' . strtoupper($user->role) . ').');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password portal salah atau tidak terdaftar.',
        ])->onlyInput('email');
    }

    /**
     * Log user out from internal portal.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')
            ->with('success', 'Anda telah berhasil keluar dari Portal Internal Zahiyyah\'s Fit.');
    }
}
