<?php

namespace Jhumanj\LaravelModelStats;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jhumanj\LaravelModelStats\LaravelModelStats
 */
class LaravelModelStatsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-model-stats';
    }
}
