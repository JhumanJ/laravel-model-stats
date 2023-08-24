<?php

namespace Jhumanj\LaravelModelStats;

use Illuminate\Support\Facades\Route;
use Jhumanj\LaravelModelStats\Console\InstallModelStatsPackage;
use Jhumanj\LaravelModelStats\Console\PublishCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelModelStatsServiceProvider extends PackageServiceProvider
{
    public function boot(): LaravelModelStatsServiceProvider
    {
        $this->registerPublishing();
        $this->registerCommands();

        return parent::boot();
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Services Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('laravel-model-stats')->hasConfigFile();

        if (! config('model-stats.enabled')) {
            return;
        }

        $this->loadMigrations();

        Route::middlewareGroup('model-stats', config('model-stats.middleware', []));

        $package
            ->hasViews()
            ->hasRoutes(['web']);
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/ModelStatsServiceProvider.stub' => app_path('Providers/ModelStatsServiceProvider.php'),
            ], 'model-stats-provider');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/model-stats'),
            ], 'model-stats-assets');
        }
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModelStatsPackage::class,
                PublishCommand::class,
            ]);
        }
    }

    /**
     * Load the package's migrations
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
