<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use App\Repositories\NavigationRepository;
use App\Repositories\ProductRepository;
use App\ViewComposers\UsersList;
use Illuminate\Support\Facades\{DB, Schema, View};
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot ()
    {
        Schema::defaultStringLength(191);
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
        $this->app->singleton(CartRepository::class, function ($app) {
            return new CartRepository($app->make(ProductRepository::class), $app->make(Guard::class));
        });
    }

     /**
      * Get the services provided by the provider.
      *
      * @return array
      */
    public function provides ()
    {
        return [CartRepository::class];
    }
}
