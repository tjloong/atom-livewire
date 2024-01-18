<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FootprintCommand extends Command
{
    protected $signature = 'atom:footprint {tables}';
    protected $description = 'Add footprint to all tables.';

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
        collect(explode(',', $this->argument('tables')))
            ->map('trim')
            ->filter(fn($tablename) => !Schema::hasColumn($tablename, 'footprint'))
            ->each(function($tablename) {
                Schema::table($tablename, function(Blueprint $table) {
                    $table->json('footprint')->nullable();
                });
            });
    }
}