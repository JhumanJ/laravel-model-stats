<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('model-stats.routes_prefix'))
    ->middleware('model-stats')
    ->name(config('model-stats.route_names_prefix'))->group(function () {
        Route::get('/{view?}', [\Jhumanj\LaravelModelStats\Http\Controllers\HomeController::class, 'home'])->name('dashboard');
    });
