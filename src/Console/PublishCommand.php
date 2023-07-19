<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishCommand extends Command
{
    protected $signature = 'atom:publish
                            {module? : Component to be published.}
                            {--force : Force overwrite if file exists.}
                            {--routes : Publish routes only.}
                            {--models : Publish models only.}
                            {--config : Show config for module.}';

    protected $description = 'Publish Atom\'s modules.';

    protected $modules = [
        'base' => [
            'models' => ['User'],
            'livewire' => [],
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
    public function handle(): void
    {
        if ($module = $this->argument('module')) {
            $config = $this->getModuleConfig($module);

            if ($this->option('config')) dump($config);
            else if ($config) {
                $confirm = $this->option('force') || $this->confirm(
                    $module === 'base'
                        ? 'This will publish Atom base. You should only do this once. Continue?'
                        : 'Publish '.$module.', continue?'
                    , true
                );

                if (!$confirm) return;

                if ($this->option('routes')) $this->publishRoutes($config);
                if ($this->option('models')) $this->publishModels($config);

                if (!$this->option('routes') && !$this->option('models')) {
                    // $this->publishLivewire($config);
                    $this->publishModels($config);
                    // $this->publishEnums($config);
                    // $this->publishJobs($config);
                    // $this->publishNotifications($config);
                    $this->publishRoutes($config);
                }

                if ($module === 'base') {
                    // publish base stubs
                    $this->call('vendor:publish', ['--tag' => 'atom-base', '--force' => true]);
                }
            }
            else $this->error('Unable to find module '.$module);
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

        $config = $this->modules[$module];

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

    // publish routes
    public function publishRoutes($config): void
    {
        foreach (data_get($config, 'routes') as $route) {
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
        $models = collect(data_get($config, 'models'))
            ->map(fn($s) => str($s)->finish('.php'))
            ->map(fn($s) => ['src/Models/'.$s, 'app/Models/'.$s]);

        if ($models->count()) {
            $this->copy($models);
            $this->newLine();
        }
    }

    // publish livewire
    public function publishLivewire($module): void
    {
        $path = $this->getConfig($module, 'livewire', $module);

        $this->copy([
            // for classes
            [
                format_class_path($path, 'src/Http/Livewire/'), 
                format_class_path($path, 'app/Http/Livewire/'),
            ],
            // for views
            [
                format_view_path($path, 'resources/views/livewire/'), 
                format_view_path($path, 'resources/views/livewire/'),
            ],
        ]);

        $this->newLine();
    }

    // publish enums
    public function publishEnums($module): void
    {
        $enums = collect($this->getConfig($module, 'enums'))
            ->map(fn($s) => ['src/Enums/'.$s, 'app/Enums/'.$s]);

        if ($enums->count()) {
            $this->copy($enums);
            $this->newLine();
        }
    }

    // publish jobs
    public function publishJobs($module): void
    {
        $jobs = collect($this->getConfig($module, 'jobs'))
            ->map(fn($s) => ['src/Jobs/'.$s, 'app/Jobs/'.$s]);

        if ($jobs->count()) {
            $this->copy($jobs);
            $this->newLine();
        }
    }

    // publish notifications
    public function publishNotifications($module): void
    {
        $notifications = collect($this->getConfig($module, 'notifications'))
            ->map(fn($s) => ['src/Notifications/'.$s, 'app/Notifications/'.$s]);

        if ($notifications->count()) {
            $this->copy($notifications);
            $this->newLine();
        }
    }

    // get livewire components
    public function getLivewireComponents(): array
    {
        $path = __DIR__.'/../Http/Livewire';
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        return collect($iterator)
            ->map(fn($file) => $file->getPathname())
            ->map(fn($val) => str($val)->replace($path.'/', '')->toString())
            ->reject(fn($val) => in_array($val, ['.', '..']) || str($val)->endsWith('..'))
            ->values()
            ->map(function($val) {
                $val = str()->replace('/.', '', $val);

                if ($isRoot = str($val)->split('/\//')->count() === 1) return null;
                else {
                    $val = str()->replaceLast('.php', '', $val);
                    $dot = str($val)->split('/\//')->map(fn($val) => str()->kebab($val))->join('.');

                    if (str($dot)->split('/\./')->count() === 2) $this->newLine();

                    return $dot;
                }
            })
            ->filter()
            ->sort()
            ->values()
            ->toArray();
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
                    else File::copy($from, $to);
    
                    $this->info("Copied $from to $to");
                    $this->replaceNamespace($to);
                }
                else {
                    $this->warn("Destination $to exists.");
                }
            }
        }
    }
}