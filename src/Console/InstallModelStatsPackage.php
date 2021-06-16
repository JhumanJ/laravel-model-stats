<?php


namespace Jhumanj\LaravelModelStats\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallModelStatsPackage extends Command
{
    protected $signature = 'model-stats:install';

    protected $description = 'Install the ModelStatsPackage';

    public function handle()
    {
        $this->info('Installing BlogPackage...');

        $this->comment('Publishing ModelStats Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'model-stats-provider']);

        $this->comment('Publishing ModelStats Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'model-stats-config']);

        $this->registerModelStatsServiceProvider();

        $this->info('ModelStats scaffolding installed successfully.');
    }

    /**
     * Register the ModelStats service provider in the application configuration file.
     *
     * @return void
     */
    private function registerModelStatsServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\ModelStatsServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\ModelStatsServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/ModelStatsServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/ModelStatsServiceProvider.php'))
        ));
    }
}
