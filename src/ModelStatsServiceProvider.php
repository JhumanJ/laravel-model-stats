<?php

namespace Jhumanj\LaravelModelStats;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ModelStatsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->authorization();
    }

    /**
     * Configure the ModelStats authorization services.
     *
     * @return void
     */
    protected function authorization(): void
    {
        $this->gate();

        LaravelModelStats::auth(function (Request $request) {
            return app()->environment('local') ||
                Gate::check('viewModelStats', [$request->user()]);
        });
    }

    /**
     * Register the ModelStats gate.
     *
     * This gate determines who can access ModelStats in non-local environments.
     *
     * @return void
     */
    protected function gate(): void
    {
        Gate::define('viewModelStats', function ($user) {
            return in_array($user->email, [//
            ], true);
        });
    }
}
