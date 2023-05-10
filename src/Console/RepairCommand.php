<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class RepairCommand extends Command
{
    protected $signature = 'atom:repair';
    protected $description = 'Repair settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        model('site_setting')->repair();
        $this->call('atom:refresh');
    }
}