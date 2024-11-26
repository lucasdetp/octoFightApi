<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Battle;
use App\Observers\BattleObserver;

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
    public function boot()
    {
        if (!class_exists(BattleObserver::class)) {
            logger('BattleObserver not found! ERROR');
        }
        Battle::observe(BattleObserver::class);
        Schema::defaultStringLength(191); // Définir la longueur des chaînes à 191
    }
}
