<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        $userRole = $request->user()->role;

        // Check if user has one of the allowed roles
        if (! in_array($userRole, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akses ditolak: Anda tidak memiliki hak akses untuk fitur ini.',
                ], 403);
            }

            return response()->view('errors.403', [
                'message' => 'Anda masuk sebagai role [' . strtoupper($userRole) . ']. Halaman ini hanya dapat diakses oleh: ' . strtoupper(implode(', ', $roles)) . '.',
            ], 403);
        }

        return $next($request);
    }
}
