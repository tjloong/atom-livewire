<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    protected $signature = 'atom:migrate
                            {module : Module to be migrated. You must migarte base module first, then follow by other modules.}
                            {--force : Force run (no interaction).}';

    protected $description = 'Migrate Atom modules.';

    public $migrations;

    // create new command instance
    public function __construct()
    {
        parent::__construct();

        $this->migrations = collect(scandir(atom_path('database/migrations')))
            ->reject(fn($val) => in_array($val, ['.', '..']))
            ->map(fn($val) => atom_path('database/migrations/'.$val, true))
            ->values();
    }

    // execute the console command
    public function handle(): void
    {
        $module = $this->argument('module');

        if ($module === 'base') {
            if (
                $this->option('force')
                || $this->confirm('This will migrate Atom base tables, are you sure?', true)
            ) {
                foreach ([
                    'app.user',
                    'app.signup',
                    'app.file',
                    'app.label',
                    'app.setting',
                    'app.page',
                    'app.enquiry',
                    'app.notilog',
                ] as $module) {
                    $this->call('atom:migrate', ['module' => $module]);
                }
            }
        }
        else {
            if ($path = $this->getMigration($module)) {
                $this->call('migrate', [
                    '--path' => $path,
                    '--force' => $this->option('force'),
                ]);
            }
            else $this->error('Unable to find migration for '.$module);
        }
    }

    // get migration
    public function getMigration($module): mixed
    {
        $name = str($module)->replace('.', '_');
        $migration = $this->migrations->first(fn($val) => str($val)->is('*_install_atom_'.$name.'.php'));

        return $migration;
    }
}