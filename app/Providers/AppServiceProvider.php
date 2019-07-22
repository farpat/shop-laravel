<?php

namespace App\Providers;

use App\Repositories\NavigationRepository;
use App\ViewComposers\UsersList;
use Illuminate\Support\Facades\{DB, Schema, View};
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
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
//        Passport::ignoreMigrations();
    }
}
