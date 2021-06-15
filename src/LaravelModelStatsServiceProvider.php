<?php

namespace Jhumanj\LaravelModelStats;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Jhumanj\LaravelModelStats\Commands\LaravelModelStatsCommand;

class LaravelModelStatsServiceProvider extends PackageServiceProvider
{
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
            ->hasMigration('create_laravel-model-stats_table')
            ->hasCommand(LaravelModelStatsCommand::class);
    }
}
