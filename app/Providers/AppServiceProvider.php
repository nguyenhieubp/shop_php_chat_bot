<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('botman', function ($app) {
            \BotMan\BotMan\Drivers\DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
            return \BotMan\BotMan\BotManFactory::create(config('botman', []), new \BotMan\BotMan\Cache\LaravelCache());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
