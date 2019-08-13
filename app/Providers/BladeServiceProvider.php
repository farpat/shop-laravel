<?php

namespace App\Providers;

use App\Repositories\{CartRepository, ModuleRepository};
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
     * @param CartRepository $cartRepository
     * @param ModuleRepository $moduleRepository
     * @param View $view
     *
     * @return void
     */
    public function boot (CartRepository $cartRepository, ModuleRepository $moduleRepository, View $view)
    {
        $view->share([
            'currency'  => $moduleRepository->getParameter('home', 'currency')->value,
            'cartItems' => $cartRepository->getItems()
        ]);
    }
}
