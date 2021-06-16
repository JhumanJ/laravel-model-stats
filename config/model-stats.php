<?php

/**
 * Config for JhumanJ/laravel-model-stats package.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | ModelStats Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable all ModelStats watchers regardless
    | of their individual configuration, which simply provides a single
    | and convenient way to enable or disable ModelStats data storage.
    |
    */

    'enabled' => env('MODEL_STATS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Telescope route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */
    'middleware' => [
        'web',
        \Jhumanj\LaravelModelStats\Http\Middleware\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | You can change the route where your dashboards are. By default routes will
    | be starting the '/stats' prefix, and names will start with 'stats.'.
    |
    */
    'routes_prefix' => 'stats',
    'route_names_prefix' => 'stats.',
];
