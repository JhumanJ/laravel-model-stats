<?php

namespace Jhumanj\LaravelModelStats;

use Illuminate\Support\Facades\Route;
use Jhumanj\LaravelModelStats\Console\InstallModelStatsPackage;
use Jhumanj\LaravelModelStats\Console\PublishCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelModelStatsServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->registerPublishing();
        $this->registerCommands();

        if (! config('model-stats.enabled')) {
            return;
        }

        Route::middlewareGroup('model-stats', config('model-stats.middleware', []));

        return parent::boot();
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-model-stats')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoutes(['web'])
            ->hasMigration('create_laravel-model-stats_table');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../stubs/ModelStatsServiceProvider.stub' => app_path('Providers/ModelStatsServiceProvider.php'),
            ], 'model-stats-provider');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/model-stats'),
            ], 'model-stats-assets');
        }
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallModelStatsPackage::class,
                PublishCommand::class,
            ]);
        }
    }
}
