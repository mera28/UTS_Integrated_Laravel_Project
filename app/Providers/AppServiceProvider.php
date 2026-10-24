<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(function () {
            if (\Illuminate\Support\Facades\Auth::user()?->role === 'customer') {
                return route('shop.index');
            }

            return route('dashboard');
        });
    }
}
