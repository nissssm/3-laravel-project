<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Cart::class);
    }

    public function boot(): void
    {
        View::composer('layouts.shop', function ($view) {
            $view->with('cartCount', app(Cart::class)->count());
            $view->with('navCategories', Category::query()->orderBy('name')->get());
        });
    }
}
