<?php

namespace CrescentPurchasing\FilamentAuditing\Commands;

use Illuminate\Console\Command;

class FilamentAuditingCommand extends Command
{
    public $signature = 'filament-auditing';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
