<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as View;

class BladeServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register ()
    {
    }

    /**
     * Bootstrap services.
     *
     * @param ProductRepository $productRepository
     *
     * @param CartRepository $cartRepository
     * @param View $view
     *
     * @return void
     */
    public function boot (ProductRepository $productRepository, CartRepository $cartRepository, View $view)
    {
        $view->share([
            'allProductReferences' => $productRepository->getAllReferences(),
            'cartItems'     => []
        ]);
    }
}
