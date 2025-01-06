<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\Facades\Actions;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Billets\Contracts\BilletGeneratorInterface::class,
            \App\Services\Billets\Generators\LogBilletGenerator::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->registerActionsCommands();
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    /**
     * Register the commands for the actions package.
     */
    private function registerActionsCommands(): void
    {
        if ($this->app->runningInConsole()) {
            Actions::registerCommands();
        }
    }
}
