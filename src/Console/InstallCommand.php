<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'atom:install
                            {--force : Force publishing}
                            {--static : Install static site}';
    protected $description = 'Install Atom';

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
        $this->info('Start Atom installation...');

        $this->publish();
        $this->nodepackages();
        $this->configs();

        $this->newLine();
        $this->info('Atom installation done');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }

    /**
     * Publishing files
     * 
     * @return void
     */
    private function publish()
    {
        $this->newLine(2);
        $this->info('Publishing Jiannius\Atom\AtomServiceProvider');

        $static = $this->option('static') ? '-static' : '';

        $this->call('vendor:publish', [
            '--provider' => 'Jiannius\Atom\AtomServiceProvider',
            '--tag' => 'atom-installation' . $static,
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'Jiannius\Atom\AtomServiceProvider',
            '--tag' => 'atom-layouts' . $static,
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'Jiannius\Atom\AtomServiceProvider',
            '--tag' => 'atom-assets' . $static,
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Update node packages
     * 
     * @return void
     */
    public function nodepackages()
    {
        $this->newLine(2);
        $this->info('Update node packages');

        // dev dependencies
        $this->updateNodePackages(function ($packages) {
            return [
                'postcss' => '^8.4.5',
                'postcss-import' => '^14.0.2',
                'postcss-nesting' => '^10.1.2'
            ] + $packages;
        });

        // dependencies
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.3.0',
                '@tailwindcss/typography' => '^0.3.0',
                'alpinejs' => '^3.4.2',
                'boxicons' => '^2.0.9',
                'dayjs' => '^1.10.7',
                'flatpickr' => '^4.6.9',
                'tailwindcss' => '^2',
            ] + $packages;
        }, false);
    }

    /**
     * Configurations
     * 
     * @return void
     */
    private function configs()
    {
        $this->newLine(2);
        
        $this->info('Configuring Route service provider...');
        replace_in_file(
            'public const HOME = \'/home\';',
            'public const HOME = \'/\';',
            app_path('Providers/RouteServiceProvider.php')
        );

        // link storage
        if (!file_exists(public_path('storage'))) {
            $this->info('Configuring storage link...');
            $this->call('storage:link');
        }
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
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