<?php

namespace Zek\EmailReset\Commands;

use Illuminate\Console\Command;

class EmailResetCommand extends Command
{
    public $signature = 'laravel-email-reset';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
