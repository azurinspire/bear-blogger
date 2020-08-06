<?php

namespace AzurInspire\BearBlogger\Commands;

use Illuminate\Console\Command;

class BearBloggerCommand extends Command
{
    public $signature = 'bear-blogger';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
