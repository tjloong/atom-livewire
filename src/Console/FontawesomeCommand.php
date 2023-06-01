<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FontawesomeCommand extends Command
{
    protected $signature = 'atom:fontawesome';
    protected $description = 'Copy fontawesome.';

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
        $this->warn('Copying fontawesome folders...');

        foreach (['webfonts', 'css'] as $dir) {
            $src = atom_path('fontawesome/'.$dir);
            $dst = base_path('public/fontawesome/'.$dir);

            File::ensureDirectoryExists($dst);
            File::copyDirectory($src, $dst);
        }

        $this->line('Done!');
        $this->newLine(2);
    }
}