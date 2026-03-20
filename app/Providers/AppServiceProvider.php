<?php

namespace App\Providers;

use App\Kafka\EventDispatcher;
use App\Kafka\Handlers\UserCreatedHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->tag([
            UserCreatedHandler::class,
        ], 'event.handlers');

        $this->app->bind(EventDispatcher::class, function ($app) {
            return new EventDispatcher(
                $app->tagged('event.handlers')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
