<?php

namespace Jhumanj\LaravelModelStats\Commands;

use Illuminate\Console\Command;

class LaravelModelStatsCommand extends Command
{
    public $signature = 'laravel-model-stats';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
