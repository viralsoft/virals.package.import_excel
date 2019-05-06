<?php

namespace ViralsBackpack\BackPackExcel;

use Illuminate\Support\ServiceProvider;

class BackPackExcelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'viralslaravelexcel');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'viralslaravelexcel');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/backpack/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/backpackexcel.php', 'backpackexcel');

        // Register the service the package provides.
        $this->app->singleton('backpackexcel', function ($app) {
            return new BackPackExcel;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['backpackexcel'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/backpackexcel.php' => config_path('backpackexcel.php'),
        ], 'backpackexcel.config');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/'),
        ], 'backpackexcel.lang');

        $this->publishes([
            __DIR__.'/../public/' => public_path('vendor/viralslaravelexcel'),
        ], 'public');
    }
}
