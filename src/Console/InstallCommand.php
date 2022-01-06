<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'atom:install
                            {--force : Force publishing}
                            {--static : Static site}';

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
        $this->publish();
        $this->nodepackages();
        $this->configs();

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
        $this->info('Publishing Jiannius\Atom\AtomServiceProvider');
        $this->newLine();

        $this->call('vendor:publish', [
            '--provider' => 'Jiannius\Atom\AtomServiceProvider',
            '--tag' => $this->option('static') ? 'atom-static' : 'atom',
            '--force' => $this->option('force'),
        ]);

        $this->newLine(2);
    }

    /**
     * Configurations
     * 
     * @return void
     */
    private function configs()
    {
        $this->info('App Settings');
        $this->newLine();

        replace_in_file(
            'public const HOME = \'/home\';',
            'public const HOME = \'/\';',
            app_path('Providers/RouteServiceProvider.php')
        );

        // link storage
        $this->call('storage:link');
    }

    /**
     * Update node packages
     * 
     * @return void
     */
    public function nodepackages()
    {
        $this->info('Update node packages');

        // dev dependencies
        $this->updateNodePackages(function ($packages) {
            return [
                'postcss-import' => '^12.0.1',
                'postcss-nesting' => '^7.0.1',
                'autoprefixer' => '^10.0.2',
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

        $this->newLine(2);
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