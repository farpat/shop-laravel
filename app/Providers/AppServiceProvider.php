<?php

namespace App\Providers;

use App\Repositories\ModuleRepository;
use App\Services\Bank\CartManager;
use App\Services\Bank\StripeService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        $this->registerBankServices();
    }

    private function registerBankServices ()
    {
        $this->app->singleton(CartManager::class);

        $this->app->singleton(ModuleRepository::class);

        $this->app->singleton(StripeService::class, function (Application $app) {
            ['key' => $key, 'secret' => $secret] = $app['config']['services']['stripe'];
            return new StripeService(
                $key, $secret,
                $app->make(ModuleRepository::class)->getParameter('home', 'currency')->value
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides ()
    {
        return [CartManager::class, StripeService::class, ModuleRepository::class];
    }
}
