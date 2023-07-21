<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishCommand extends Command
{
    protected $signature = 'atom:publish
                            {module? : Module to be published. Use "models.<model>" to publish model. User "routes.<route>" to publish route.}
                            {--force : Force overwrite if file exists.}
                            {--config : Show config for module.}';

    protected $description = 'Publish Atom\'s modules.';

    protected $modules = [
        'base' => [
            'models' => ['User'],
            'routes' => [
                'auth/socialite.php',
                'auth/login.php',
                'auth/register.php',
                'auth/verification.php',
                'app/dashboard.php',
                'app/settings.php',
                'web/home.php',
            ],
        ],
    ];

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
            else if (str($module)->is('routes.*')) return $this->publishRoutes($module);
            else if (str($module)->is('enums.*')) return $this->publishEnums($module);
            else if (str($module)->is('jobs.*')) return $this->publishJobs($module);
            else if (str($module)->is('notifications.*')) return $this->publishNotifications($module);
            else if ($config = $this->getModuleConfig($module)) {
                if ($this->option('config')) return dump($config);

                $confirm = $this->option('force') || $this->confirm(
                    $module === 'base'
                        ? 'This will publish Atom base. You should only do this once. Continue?'
                        : 'Publish '.$module.', continue?'
                    , true
                );

                if (!$confirm) return $this->line('Action Canceled.');

                $this->publishLivewire($config);
                $this->publishModels($config);
                $this->publishEnums($config);
                $this->publishJobs($config);
                $this->publishNotifications($config);
                $this->publishRoutes($config);

                if ($module === 'base') {
                    // publish base stubs
                    $this->call('vendor:publish', ['--tag' => 'atom-base', '--force' => true]);
                }
            }
            else {
                return $this->publishLivewire($module);
            }
        }
        else {
            if ($module = $this->choice('Please choose a module', array_keys($this->modules))) {
                $this->call('atom:publish', [
                    'module' => $module,
                    '--force' => $this->option('force'),
                    '--routes' => $this->option('routes'),
                    '--models' => $this->option('models'),
                ]);
            }
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
                'routes' => (array) data_get($config, 'routes', str(format_view_path($module))->finish('.php')->toString()),
                'livewire' => (array) data_get($config, 'livewire', format_class_path($module)),
                'enums' => (array) data_get($config, 'enums', []),
                'jobs' => (array) data_get($config, 'jobs', []),
                'notifications' => (array) data_get($config, 'notifications', []),
            ];
        }
        else {

        }
    }

    // publish routes
    public function publishRoutes($config): void
    {
        $this->info('Publishing routes...');

        $routes = is_string($config)
            ? (array) str(format_view_path($config))->finish('.php')->toString()
            : data_get($config, 'routes');

        foreach ($routes as $route) {
            $path = atom_path(str($route)->start('routes/')->toString());

            if (!file_exists($path)) $this->error('Unable to find route at '.$path);
            else {
                $source = file_get_contents($path);
                $source = str($source)->replace("<?php\n", "")->trim();
                $topath = base_path('routes/web.php');
                $target = file_get_contents($topath);
                $content = str($target);
    
                if ($content->contains($source)) $this->warn("Routes for $route already configured in routes/web.php.");
                else {
                    $content = $content->append("\n\n".$source)->toString();
                    file_put_contents($topath, $content);
                    $this->info("Appended $route to routes/web.php.");
                }
            }
        }
        
        $this->newLine();
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
            ->map(fn($s) => ['src/Models/'.$s, 'app/Models/'.$s]);

        if ($models->count()) {
            $this->copy($models);
            $this->newLine();
        }
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