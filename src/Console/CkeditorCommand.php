<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CkeditorCommand extends Command
{
    protected $signature = 'atom:ckeditor';
    protected $description = 'Copy CKEditor.';

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
        $this->warn('Copying ckeditor...');

        $src = atom_path('ckeditor/build');
        $dst = base_path('public/ckeditor');

        File::ensureDirectoryExists($dst);
        File::copyDirectory($src, $dst);

        $this->line('Done!');
        $this->newLine(2);
    }
}