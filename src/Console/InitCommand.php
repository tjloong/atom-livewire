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
            "@alpinejs/collapse" => '^3',
            '@ryangjchandler/alpine-hooks' => '^1',
            '@tailwindcss/typography' => '^0.5',
            'alpinejs' => '^3',
            'autoprefixer' => '^10',
            'flatpickr' => '^4',
            'postcss' => '^8',
            'postcss-import' => '^14',
            'tailwindcss' => '^3',
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