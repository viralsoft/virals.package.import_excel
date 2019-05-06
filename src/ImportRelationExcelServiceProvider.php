<?php

namespace ViralsLaravel\ImportRelationExcel;

use Illuminate\Support\ServiceProvider;

class ImportRelationExcelServiceProvider extends ServiceProvider
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
        $this->mergeConfigFrom(__DIR__.'/../config/importrelationexcel.php', 'importrelationexcel');

        // Register the service the package provides.
        $this->app->singleton('importrelationexcel', function ($app) {
            return new ImportRelationExcel;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['importrelationexcel'];
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
            __DIR__.'/../config/importrelationexcel.php' => config_path('importrelationexcel.php'),
        ], 'importrelationexcel.config');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/'),
        ], 'importrelationexcel.lang');

        $this->publishes([
            __DIR__.'/../public/' => public_path('vendor/viralslaravelexcel'),
        ], 'public');
    }
}
