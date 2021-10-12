<?php

namespace Jhumanj\LaravelModelStats\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'model-stats:publish {--force : Overwrite any existing files}';

    protected $description = 'Publish all of the ModelStats resources';

    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'model-stats-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'model-stats-assets',
            '--force' => true,
        ]);
    }
}
