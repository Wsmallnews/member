<?php

namespace Wsmallnews\Member\Commands;

use Illuminate\Console\Command;

class MemberCommand extends Command
{
    public $signature = 'member';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
