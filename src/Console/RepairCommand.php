<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class RepairCommand extends Command
{
    protected $signature = 'atom:repair';
    protected $description = 'Repair settings';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        model('setting')->repair();

        $this->call('atom:refresh');
    }
}