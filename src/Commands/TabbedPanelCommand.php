<?php

namespace Aldesrahim\TabbedPanel\Commands;

use Illuminate\Console\Command;

class TabbedPanelCommand extends Command
{
    public $signature = 'tabbed-panel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
