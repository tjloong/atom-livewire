<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class RefreshCommand extends Command
{
    protected $signature = 'atom:refresh';
    protected $description = 'Refresh Laravel';

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
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('event:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('basset:clear');
        $this->call('livewire:discover');
    }
}