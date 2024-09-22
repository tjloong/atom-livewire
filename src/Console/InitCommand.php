<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitCommand extends Command
{
    protected $signature = 'atom:init
                            {--force : Force run}';

    protected $description = 'Initalize atom.';

    protected $hidden = true;

    protected $node = [
        'devDependencies' => [
            '@alpinejs/anchor' => '^3',
            '@alpinejs/collapse' => '^3',
            '@alpinejs/intersect' => '^3',
            '@alpinejs/mask' => '^3',
            '@alpinejs/sort' => '^3',
            '@marcreichel/alpine-autosize' => '^1',
            '@ryangjchandler/alpine-hooks' => '^1',
            '@ryangjchandler/alpine-tooltip' => '^2',
            '@tailwindcss/typography' => '^0.5',
            '@tiptap/core' => '^2',
            '@tiptap/extension-bubble-menu' => '^2',
            '@tiptap/extension-color' => '^2',
            '@tiptap/extension-floating-menu' => '^2',
            '@tiptap/extension-highlight' => '^2',
            '@tiptap/extension-horizontal-rule' => '^2',
            '@tiptap/extension-image' => '^2',
            '@tiptap/extension-link' => '^2',
            '@tiptap/extension-mention' => '^2',
            '@tiptap/extension-placeholder' => '^2',
            '@tiptap/extension-subscript' => '^2',
            '@tiptap/extension-superscript' => '^2',
            '@tiptap/extension-table' => '^2',
            '@tiptap/extension-table-cell' => '^2',
            '@tiptap/extension-table-header' => '^2',
            '@tiptap/extension-table-row' => '^2',
            '@tiptap/extension-text-align' => '^2',
            '@tiptap/extension-text-style' => '^2',
            '@tiptap/extension-underline' => '^2',
            '@tiptap/extension-youtube' => '^2',
            '@tiptap/pm' => '^2',
            '@tiptap/starter-kit' => '^2',
            '@tiptap/suggestion' => '^2',
            'alpinejs' => '^3',
            'autoprefixer' => '^10',
            'dayjs' => '^1',
            'flatpickr' => '^4',
            'laravel-echo' => '^1',
            'postcss' => '^8',
            'postcss-import' => '^14',
            'pusher-js' => '^8',
            'tailwindcss' => '^3',
            'ulid' => '^2',
        ],
        'dependencies' => [
            // 
        ],
    ];

    // create new command instance
    public function __construct()
    {
        parent::__construct();
    }

    // execute the console command
    public function handle(): void
    {
        if (
            $this->option('force') 
            || $this->confirm('Initialization should only be runned at the first installation of Atom. Are you sure?', true)
        ) {
            $this->info('Resetting Laravel...');
            $this->resetLaravel();
            $this->line('Done.');
            $this->newLine();
            
            $this->info('Updating package.json...');
            $this->updatePackageJson();
            $this->line('Done.');
            $this->newLine();
            
            $this->info('Updating HOME in RouteServiceProvider.php...');
            $this->updateHomeRoute();
            $this->line('Done.');
            $this->newLine();
            
            $this->info('Running storage:link...');
            $this->linkStorage();
            $this->line('Done.');
            $this->newLine();
        }
    }

    // reset laravel
    public function resetLaravel()
    {
        collect([
            base_path('app/Models/User.php'),
            base_path('resources/views/welcome.blade.php'),
            base_path('resources/js/bootstrap.js'),
            base_path('resources/js/app.js'),
            base_path('resources/css/app.css'),
        ])->each(function($path) {
            if (File::exists($path)) File::delete($path);
        });

        $routes = base_path('routes/web.php');
        file_put_contents($routes, "<?php\n\n\$route = app('route');\n");
    }

    // update package.json
    public function updatePackageJson()
    {
        // dev dependencies
        $this->writeToPackageJson(function ($packages) {
            return $this->node['devDependencies'] + $packages;
        });

        // dependencies
        $this->writeToPackageJson(function ($packages) {
            return $this->node['dependencies'] + $packages;
        }, false);
    }

    // update home route
    public function updateHomeRoute()
    {
        replace_in_file(
            'public const HOME = \'/home\';',
            'public const HOME = \'/\';',
            app_path('Providers/RouteServiceProvider.php')
        );
    }

    // run storage:link
    private function linkStorage()
    {
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }
    }

    // write to package.json
    public function writeToPackageJson(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }
}