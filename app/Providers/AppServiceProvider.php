<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        // Add cart data to all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // User is logged in - get their latest cart
                $cart = Auth::user()->carts()
                    ->with(['items.product.primaryImage'])
                    ->latest()
                    ->first();
            } else {
                // User is a guest - use session ID to track cart
                $sessionId = session()->getId();
                
                // Try to find an existing cart with this session ID
                $cart = Cart::where('session_id', $sessionId)
                    ->with(['items.product.primaryImage'])
                    ->latest()
                    ->first();
            }
            
            if ($cart) {
                $view->with([
                    'cart_count' => $cart->getTotalItemsAttribute(),
                    'cart_items' => $cart->items,
                    'cart_total' => $cart->getTotalAttribute()
                ]);
            } else {
                $view->with([
                    'cart_count' => 0,
                    'cart_items' => [],
                    'cart_total' => 0
                ]);
            }
        });
    }
}
