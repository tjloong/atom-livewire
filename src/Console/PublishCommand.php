<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishCommand extends Command
{
    protected $signature = 'atom:publish
                            {module? : Module to be published. Use "models.<model>" to publish model. User "enums.<enum>" to publish enum.}
                            {--force : Force overwrite if file exists.}
                            {--config : Show config for module.}';

    protected $description = 'Publish Atom\'s modules.';

    protected $modules = [];

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
    public function handle()
    {
        if ($module = $this->argument('module')) {
            if (str($module)->is('models.*')) return $this->publishModels($module);
            else if (str($module)->is('enums.*')) return $this->publishEnums($module);
            else if (str($module)->is('jobs.*')) return $this->publishJobs($module);
            else if (str($module)->is('notifications.*')) return $this->publishNotifications($module);
            else {
                $config = $this->getModuleConfig($module);
                if ($this->option('config')) return dump($config);

                if (!$this->option('force') && !$this->confirm(
                    $module === 'base'
                        ? 'This will publish Atom base. You should only do this once. Continue?'
                        : 'Publish '.$module.', continue?'
                    , true
                )) return $this->line('Action Cancelled.');

                if ($config) {
                    $this->publishLivewire($config);
                    $this->publishModels($config);
                    $this->publishEnums($config);
                    $this->publishJobs($config);
                    $this->publishNotifications($config);
                }
                else {
                    $this->publishLivewire($module);
                }

                // publish base stubs
                if ($module === 'base') {
                    $this->call('vendor:publish', ['--tag' => 'atom-base', '--force' => true]);
                }
            }
        }
        elseif ($module = $this->choice('Please choose a module', array_keys($this->modules))) {
            $this->call('atom:publish', [
                'module' => $module,
                '--force' => $this->option('force'),
            ]);
        }
    }

    // get module config
    public function getModuleConfig($module): mixed
    {
        if (!in_array($module, array_keys($this->modules))) return null;

        if ($config = $this->modules[$module]) {
            return [
                'module' => $module,
                'models' => (array) data_get($config, 'models', str()->studly(str($module)->split('/\./')->last())),
                'livewire' => (array) data_get($config, 'livewire', format_class_path($module)),
                'enums' => (array) data_get($config, 'enums', []),
                'jobs' => (array) data_get($config, 'jobs', []),
                'notifications' => (array) data_get($config, 'notifications', []),
            ];
        }
        else {

        }
    }

    // publish models
    public function publishModels($config): void
    {
        $this->info('Publishing models...');

        $models = collect(
            is_string($config)
                ? str($config)->replace('models.', '')->studly()
                : data_get($config, 'models')
        )
            ->map(fn($s) => str($s)->finish('.php'))
            ->map(fn($s) => ['src/Models/'.$s, 'app/Models/'.$s])
            ->filter(fn($val) => file_exists(atom_path($val[0])));

        if ($models->count()) $this->copy($models);
        else $this->line('Nothing to publish.');

        $this->newLine();
    }

    // publish livewire
    public function publishLivewire($config): void
    {
        $this->info('Publishing livewire components...');

        $components = is_string($config)
            ? (array) $config
            : data_get($config, 'livewire');

        $files = collect();

        foreach ($components as $component) {
            // class
            $source = format_class_path($component, 'src/Http/Livewire/');
            $target = format_class_path($component, 'app/Http/Livewire/');
    
            if (File::exists(atom_path($source))) $files->push([$source, $target]);
            else {
                $source = str($source)->finish('.php')->toString();
                $target = str($target)->finish('.php')->toString();
    
                if (File::exists(atom_path($source))) $files->push([$source, $target]);
            }

            // view
            $source = format_view_path($component, 'resources/views/livewire/');
            $target = format_view_path($component, 'resources/views/livewire/');

            if (File::exists(atom_path($source))) $files->push([$source, $target]);
            else {
                $source = str($source)->finish('.blade.php')->toString();
                $target = str($target)->finish('.blade.php')->toString();
    
                if (File::exists(atom_path($source))) $files->push([$source, $target]);
            }            
        };

        if ($files->count()) $this->copy($files->toArray());
        else $this->line('Nothing to publish.');
        
        $this->newLine();
    }

    // publish enums
    public function publishEnums($config): void
    {
        $enums = collect(
            is_string($config)
                ? format_class_path(str($config)->replace('enums.', '')->finish('.php'))
                : data_get($config, 'enums')
        )->map(fn($s) => ['src/Enums/'.$s, 'app/Enums/'.$s]);

        if ($enums->count()) {
            $this->copy($enums->toArray());
            $this->newLine();
        }
    }

    // publish jobs
    public function publishJobs($config): void
    {
        $jobs = collect(
            is_string($config)
                ? format_class_path(str($config)->replace('jobs.', '')->finish('.php'))
                : data_get($config, 'jobs')
        )->map(fn($s) => ['src/Jobs/'.$s, 'app/Jobs/'.$s]);

        if ($jobs->count()) {
            $this->copy($jobs->toArray());
            $this->newLine();
        }
    }

    // publish notifications
    public function publishNotifications($config): void
    {
        $notifications = collect(
            is_string($config)
                ? format_class_path(str($config)->replace('notifications.', '')->finish('.php'))
                : data_get($config, 'notifications')
        )->map(fn($s) => ['src/Notifications/'.$s, 'app/Notifications/'.$s]);

        if ($notifications->count()) {
            $this->copy($notifications->toArray());
            $this->newLine();
        }
    }

    // replace namespace
    public function replaceNamespace($path): void
    {
        if (File::isDirectory($path)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            $files = collect($iterator)
                ->map(fn($file) => $file->getPathname())
                ->reject(fn($file) => str($file)->endsWith('.') || str($file)->endsWith('..'))
                ->values();
        }
        else $files = collect([$path]);

        foreach ($files as $file) {
            replace_in_file('namespace Jiannius\Atom', 'namespace App', $file);
        }
    }

    // copy
    public function copy($files): void
    {
        foreach ($files as $file) {
            $from = atom_path($file[0]);
            $to = base_path($file[1]);

            if (File::exists($from)) {
                if ($this->option('force') || !File::exists($to)) {
                    if (File::isDirectory($from)) File::copyDirectory($from, $to);
                    else {
                        $segments = collect(explode('/', $to));
                        $segments->pop();
                        $directory = $segments->join('/');
                        
                        if (!File::exists($directory)) File::makeDirectory($directory, 0755, true, true);

                        File::copy($from, $to);
                    }
    
                    $this->line("Copied $from to $to");
                    $this->replaceNamespace($to);
                }
                else {
                    $this->line("Destination $to exists.");
                }
            }
        }
    }
}