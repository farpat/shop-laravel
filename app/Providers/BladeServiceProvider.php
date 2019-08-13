<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\ModuleRepository;
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
     * @param ModuleRepository $moduleRepository
     * @param View $view
     *
     * @return void
     */
    public function boot (ProductRepository $productRepository, CartRepository $cartRepository, ModuleRepository $moduleRepository, View $view)
    {
        $view->share([
            'allProductReferences' => $productRepository->getAllReferences(),
            'currency'             => $moduleRepository->getParameter('home', 'currency')->value,
            'cartItems'            => []
        ]);
    }
}
