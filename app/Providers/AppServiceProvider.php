<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        \DoubleThreeDigital\SimpleCommerce\Http\Controllers\CartItemController::class =>
        \App\Http\Controllers\CartItemController::class,
        \DoubleThreeDigital\SimpleCommerce\Http\Controllers\CartController::class =>
        \App\Http\Controllers\CartController::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once(app_path("Helpers.php"));
    }
}
