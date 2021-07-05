# Laravel Model Stats
Model statistics dashboard for your Laravel Application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jhumanj/laravel-model-stats.svg?style=flat-square)](https://packagist.org/packages/jhumanj/laravel-model-stats)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/jhumanj/laravel-model-stats/run-tests?label=tests)](https://github.com/jhumanj/laravel-model-stats/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/jhumanj/laravel-model-stats/Check%20&%20fix%20styling?label=code%20style)](https://github.com/jhumanj/laravel-model-stats/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jhumanj/laravel-model-stats.svg?style=flat-square)](https://packagist.org/packages/jhumanj/laravel-model-stats)

---
<img style="margin:auto;" src="https://i.ibb.co/Mnkp3FD/Capture-d-e-cran-2021-06-25-a-10-55-32.png" alt="Capture-d-e-cran-2021-06-25-a-10-55-32" border="0">

This Laravel packages gives you a statistic dashboard for you Laravel application. Think of it as a light version of 
[Grafana](https://grafana.com/), but built-in your Laravel application, and much easier to get started with. 
No code knowledge is required to use Laravel Model Stats, users can do everything from the web interface.

---

## Installation

You can install the package via composer:

```bash
composer require jhumanj/laravel-model-stats
```

You can install the package and run the migrations with:

```bash
php artisan model-stats:install
php artisan migrate
```


## Available Widgets

Different type of widgets (daily count, daily average, etc.) are available. When creating a widget,
you choose a Model, an aggregation type and the column(s) for the graph. You can then resize and move the widgets around on your dashboard.

The aggregation types currently available:
- Daily Count (Number of records per day).
- Cumulated Daily Count (Total number of records since beginning, every day).
- Period Total (Number of new records during selected period).
- ... (more to come soon)

For each widget type, date can be any column: `created_at`,`updated_at`,`custom_date`.

## Dashboard Authorization

The ModelStats dashboard may be accessed at the `/stats` route. By default, you will only be able to access this 
dashboard in the local environment. Within your `app/Providers/ModelStatsServiceProvider.php` file, there is an 
authorization gate definition. This authorization gate controls access to ModelStats in non-local environments. 
You are free to modify this gate as needed to restrict access to your ModelStats installation: 

```php 
/**
 * Register the ModelStats gate.
 *
 * This gate determines who can access ModelStats in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewModelStats', function ($user) {
        return in_array($user->email, [
            'taylor@laravel.com',
        ]);
    });
}
```

## Upgrading

Be sure to re-publish the front-end assets when upgrading ModelStats:
```
php artisan model-stats:publish
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits (Contributors)

- [Julien Nahum](https://github.com/JhumanJ)

## Inspiration 
- [Grafana](https://grafana.com/): for the dashboard/widget aspect
- [Laravel Telescope](https://github.com/laravel/telescope): for many things in the package structure (front-end, authorization...)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
