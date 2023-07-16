<?php

namespace Jiannius\Atom\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PublishCommand extends Command
{
    protected $signature = 'atom:publish
                            {component? : Component to be published.}
                            {--force : Force overwrite if file exists.}
                            {--list : List all available components.}
                            {--routes : Publish routes only.}
                            {--models : Publish models only.}
                            {--static : Publish static site.}';

    protected $description = 'Publish Atom\'s livewire components.';

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
        if ($component = $this->argument('component')) {
            $this->newLine();

            if ($this->option('routes')) $this->publishRoutes($component);
            if ($this->option('models')) $this->publishModels($component);

            if (!$this->option('routes') && !$this->option('models')) {
                $this->publishLivewire($component);
                $this->publishModels($component);
                $this->publishEnums($component);
                $this->publishJobs($component);
                $this->publishNotifications($component);
                $this->publishRoutes($component);
            }
        }
        elseif ($this->option('list')) {
            foreach ($this->getComponents() as $val) {
                $this->line($val);
            }
        }
        else {
            $components = collect($this->getComponents())
                ->filter(fn($val) => str($val)->split('/\./')->count() === 2)
                ->map(fn($val) => str($val)->is('app.*') ? $val : str($val)->split('/\./')->first())
                ->unique()
                ->values()
                ->toArray();

            if ($component = $this->choice('Please choose a component', $components)) {
                $this->call('atom:publish', [
                    'component' => $component,
                    '--force' => $this->option('force'),
                    '--route' => $this->option('route'),
                    '--static' => $this->option('static'),
                ]);
            }
        }
    }

    // publish livewire
    public function publishLivewire($component): void
    {
        $path = $this->getConfig($component, 'livewire', $component);

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

    // publish models
    public function publishModels($component): void
    {
        $models = collect($this->getConfig($component, 'models'))
            ->map(fn($s) => str($s)->finish('.php'))
            ->map(fn($s) => ['src/Models/'.$s, 'app/Models/'.$s]);

        if ($models->count()) {
            $this->copy($models);
            $this->newLine();
        }
    }

    // publish enums
    public function publishEnums($component): void
    {
        $enums = collect($this->getConfig($component, 'enums'))
            ->map(fn($s) => ['src/Enums/'.$s, 'app/Enums/'.$s]);

        if ($enums->count()) {
            $this->copy($enums);
            $this->newLine();
        }
    }

    // publish jobs
    public function publishJobs($component): void
    {
        $jobs = collect($this->getConfig($component, 'jobs'))
            ->map(fn($s) => ['src/Jobs/'.$s, 'app/Jobs/'.$s]);

        if ($jobs->count()) {
            $this->copy($jobs);
            $this->newLine();
        }
    }

    // publish notifications
    public function publishNotifications($component): void
    {
        $notifications = collect($this->getConfig($component, 'notifications'))
            ->map(fn($s) => ['src/Notifications/'.$s, 'app/Notifications/'.$s]);

        if ($notifications->count()) {
            $this->copy($notifications);
            $this->newLine();
        }
    }

    // publish routes
    public function publishRoutes($component): void
    {
        $path = str(
            format_view_path($this->getConfig($component, 'routes', $component), 'routes/')
        )->finish('.php')->toString();

        $path = atom_path($path);

        if (!file_exists($path)) $this->error('No route configured for component '.$component);
        else {
            $source = file_get_contents($path);
            $source = str($source)->replace("<?php\n", "")->trim()->prepend("// $component (from atom)\n");
            $topath = base_path('routes/web.php');
            $target = file_get_contents($topath);
            $content = str($target);

            if ($content->contains($source)) $this->warn("Routes for $component already configured in routes/web.php.");
            else {
                $content = $content->append("\n\n".$source)->toString();
                file_put_contents($topath, $content);
                $this->info("Appended $component routes to routes/web.php.");
            }
        }
    }

    // get config
    public function getConfig($component, $key = null, $default = null): mixed
    {
        $json = json_decode(file_get_contents(atom_path('modules.config.json')), true);
        $config = $json[$component] ?? null;

        if ($key) return data_get($config, $key, $default);

        return $config;
    }

    // get components
    public function getComponents(): array
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