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

    'enabled'            => env('MODEL_STATS_ENABLED', true),
    'allow_custom_code'  => env('MODEL_STATS_CUSTOM_CODE', true),

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every ModelStats route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */
    'middleware'         => [
        'web',
        \Jhumanj\LaravelModelStats\Http\Middleware\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | ModelStats table name
    |--------------------------------------------------------------------------
    |
    | As PostgreSQL table names seems to use dashes instead of underscore
    | this configures the table name based on your connection.
    |
    */
    'table_name'         => 'model_stats_dashboards',

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | You can change the route where your dashboards are. By default, routes will
    | be starting the '/stats' prefix, and names will start with 'stats.'.
    |
    */
    'routes_prefix'      => 'stats',
    'route_names_prefix' => 'stats.',
];
