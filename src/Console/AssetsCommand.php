<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class AssetsCommand extends Command
{
    protected $signature = 'atom:assets {--force : Force publishing}';
    protected $description = 'Publish Atom\'s assets';

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
            '--tag' => 'atom-assets',
            '--force' => $this->option('force'),
        ]);
    }
}