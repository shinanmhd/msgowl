<?php

namespace Hadhiya\MsgOwl\Commands;

use Illuminate\Console\Command;

class MsgOwlCommand extends Command
{
    public $signature = 'msgowl';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
