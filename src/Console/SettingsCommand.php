<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class SettingsCommand extends Command
{
    protected $signature = 'atom:settings';
    protected $description = 'Reset settings';

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
        model('setting')->reset();
        $this->call('atom:refresh');
    }
}