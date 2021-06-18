<?php

namespace Jhumanj\LaravelModelStats\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Jhumanj\LaravelModelStats\ModelStatsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Jhumanj\\LaravelModelStats\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ModelStatsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravel-model-stats_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
