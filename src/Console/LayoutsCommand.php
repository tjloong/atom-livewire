<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class LayoutsCommand extends Command
{
    protected $signature = 'atom:layouts {--force : Force publishing}';
    protected $description = 'Publish Atom\'s layouts';

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
        $this->call('vendor:publish', [
            '--provider' => 'Jiannius\Atom\AtomServiceProvider',
            '--tag' => 'atom-layouts',
            '--force' => $this->option('force'),
        ]);
    }
}