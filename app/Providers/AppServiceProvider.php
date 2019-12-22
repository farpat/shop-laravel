<?php

namespace App\Providers;

use App\Repositories\ModuleRepository;
use App\Services\Bank\CartManager;
use App\Services\Bank\StripeService;
use App\ViewComposers\CartStoreViewComposer;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Str as BaseStr;

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

        View::composer('_partials.cart-store', CartStoreViewComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
        $this->registerBankServices();
        $this->registerMacros();
    }

    private function registerBankServices ()
    {
        $this->app->singleton(CartManager::class);

        $this->app->singleton(ModuleRepository::class);

        $this->app->singleton(StripeService::class, function (Application $app) {
            ['key' => $key, 'secret' => $secret] = $app['config']['services']['stripe'];
            return new StripeService(
                $key, $secret,
                $app->make(ModuleRepository::class)->getParameter('billing', 'currency')->value
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

    private function registerMacros ()
    {
        Str::macro('jsonDecode', function (string $string) {
            if (BaseStr::startsWith($string, ['{', '['])) {
                $newValue = json_decode($string);
                return json_last_error() === JSON_ERROR_NONE ? $newValue : null;
            }
        });
    }
}
