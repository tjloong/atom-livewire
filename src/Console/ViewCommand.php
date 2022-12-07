<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewCommand extends Command
{
    protected $signature = 'atom:view
                            {name : View name. Eg. "web.blog"}
                            {--force : Force copy.}';

    protected $description = 'Copy module\'s view.';

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
        $name = str($this->argument('name'))->replace('.', '/')->toString();
        $path = 'resources/views/livewire/'.$name;
        $src = atom_path($path);
        $dst = base_path($path);

        // copy folder
        if (File::isDirectory($src)) {
            $this->line("Copying folder $name to $dst.");

            File::ensureDirectoryExists($dst);
            File::copyDirectory($src, $dst);
            
            $this->info('Done!');
        }
        // copy file
        else {
            $filesrc = $src.'.blade.php';
            $filedst = $dst.'.blade.php';

            if (File::exists($filesrc)) {
                $this->line("Copying $name to $filedst.");

                // make sure the folder exists
                $folder = collect(explode('/', $path));
                $folder->pop();
                $folder = $folder->join('/');
                File::ensureDirectoryExists(base_path($folder));
                File::copy($filesrc, $filedst);

                $this->info('Done');
            }
            else {
                $this->error('File or directory not found.');
            }
        }

        $this->newLine(2);
    }
}