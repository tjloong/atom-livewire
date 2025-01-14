<?php

namespace Jiannius\Atom\Providers;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\View\ComponentAttributeBag;

class AtomServiceProvider extends ServiceProvider
{
    // register
    public function register() : void
    {
        //
    }

    // boot
    public function boot() : void
    {
        $this->registerHelpers();
        $this->registerViews();
        $this->registerTranslation();
        $this->registerBindings();
        $this->registerConfigs();
        $this->registerGates();
        $this->registerBladeIfs();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
        $this->registerServices();
        $this->registerTagCompiler();
        $this->registerMorphMap();
        $this->registerMacros();
        $this->registerPublishes();
        $this->registerCommands();
    }

    // register publishes
    public function registerPublishes()
    {
        if (!$this->app->runningInConsole()) return;

        $this->publishes([
            __DIR__.'/../../publishes/app' => base_path('app'),
            __DIR__.'/../../publishes/config' => base_path('config'),
            __DIR__.'/../../publishes/resources' => base_path('resources'),
            __DIR__.'/../../publishes/routes' => base_path('routes'),
            __DIR__.'/../../publishes/tailwind.config.js' => base_path('tailwind.config.js'),
            __DIR__.'/../../publishes/postcss.config.js' => base_path('postcss.config.js'),
            __DIR__.'/../../publishes/vite.config.js' => base_path('vite.config.js'),
        ], 'atom');
    }

    // register commands
    public function registerCommands()
    {
        if (!$this->app->runningInConsole()) return;

        $this->commands([
            \Jiannius\Atom\Console\CkeditorCommand::class,
            \Jiannius\Atom\Console\FontawesomeCommand::class,
            \Jiannius\Atom\Console\FootprintCommand::class,
            \Jiannius\Atom\Console\InitCommand::class,
            \Jiannius\Atom\Console\MigrateCommand::class,
            \Jiannius\Atom\Console\PublishCommand::class,
            \Jiannius\Atom\Console\RefreshCommand::class,
            \Jiannius\Atom\Console\SettingsCommand::class,
        ]);
    }

    // register helpers
    public function registerHelpers()
    {
        require_once __DIR__.'/../Helpers/Core.php';
        require_once __DIR__.'/../Helpers/Atom.php';
        require_once __DIR__.'/../Helpers/Component.php';
        require_once __DIR__.'/../Helpers/Database.php';
        require_once __DIR__.'/../Helpers/Route.php';
    }

    // register views
    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'atom');
    }

    // register translation
    public function registerTranslation()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'atom');
    }

    // register bindings
    public function registerBindings()
    {
        $this->app->bind('route', function() {
            $class = find_class('services.route');
            return new $class;
        });

        $this->app->bind('select', function() {
            $class = find_class('services.select');
            return new $class;
        });

        $this->app->bind('image', function() {
            if (extension_loaded('imagick')) return new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
            if (extension_loaded('gd')) return new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        });
    }

    // register morph map
    public function registerMorphMap()
    {
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }
    }

    // register tag compiler
    // this will make <atom:component/>
    public function registerTagCompiler()
    {
        $compiler = new \Jiannius\Atom\AtomTagCompiler(
            app('blade.compiler')->getClassComponentAliases(),
            app('blade.compiler')->getClassComponentNamespaces(),
            app('blade.compiler')
        );

        app()->bind('atom.compiler', fn () => $compiler);

        app('blade.compiler')->precompiler(function ($in) use ($compiler) {
            return $compiler->compile($in);
        });        
    }

    // register services
    public function registerServices()
    {
        \Jiannius\Atom\Services\Alert::boot();
        \Jiannius\Atom\Services\Modal::boot();
        \Jiannius\Atom\Services\Sheet::boot();
        \Jiannius\Atom\Services\Toast::boot();
        \Jiannius\Atom\Services\Confirm::boot();
    }

    // register blade components
    public function registerBladeComponents()
    {
        Blade::anonymousComponentPath(__DIR__.'/../../components', 'atom');

        $names = cache()->rememberForever('atom-blade-components', function() {
            $path = atom_path('resources/views/components/');
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
            $names = collect();

            foreach ($iterator as $file) {
                if ($file->isDir()) continue;
                $name = (string) str($file->getPathname())->replace($path, '')->replace('/', '.')->replace('.blade.php', '');
                $names->push($name);
            }

            return $names->reject(fn($name) => view()->exists('components.'.$name))->values();
        });

        foreach ($names as $name) {
            $namespace = collect(explode('.', $name))->map(fn($str) => str()->studly($str))->join('\\');
            
            if (str($namespace)->is('*\Index')) $name = (string) str($name)->replace('.index', '');

            $class = "Jiannius\Atom\Components\\$namespace";

            Blade::component($name, $class);
        }
    }

    // register blade ifs
    public function registerBladeIfs()
    {
        Blade::if('route', function() {
            return current_route(func_get_args());
        });

        Blade::if('notroute', function() {
            return !current_route(func_get_args());
        });

        Blade::if('tier', function($value) {
            return tier($value);
        });

        Blade::if('nottier', function($value) {
            return !tier($value);
        });

        Blade::if('role', function($value) {
            return user()->can('role', $value);
        });

        Blade::if('notrole', function($value) {
            return !user()->can('role', $value);
        });
    }

    // register blade directives
    public function registerBladeDirectives()
    {
        // @t() - short hand for translation
        Blade::directive('t', function ($expression) {
            return "<?php echo t($expression); ?>";
        });

        // @e() - short hand for echo
        Blade::directive('e', function ($expression) {
            return "<?php echo e($expression); ?>";
        });

        // @ee() - short hand for echo (without double encode)
        Blade::directive('ee', function ($expression) {
            return "<?php echo $expression; ?>";
        });
    }

    // register gates
    public function registerGates()
    {
        $policy = find_class('policy');

        Gate::define('tier', [$policy, 'tier']);
        Gate::define('role', [$policy, 'role']);
        Gate::define('permission', [$policy, 'permission']);
        Gate::define('perm', [$policy, 'permission']);
    }

    // register configs
    public function registerConfigs()
    {
        if (config('atom.static')) return;

        if ($this->app->runningInConsole()) {
            // basset cache paths
            config(['backpack.basset.view_paths' => [
                resource_path('views'),
                atom_path('resources/views'),
            ]]);
        }

        if (!$this->app->runningInConsole() || ($this->app->runningInConsole() && Schema::hasTable('settings'))) {
            // socialite
            foreach (model('setting')->getSocialLogins() as $provider) {
                $name = get($provider, 'name');
                $id = settings($name.'_client_id');
                $secret = settings($name.'_client_secret');
                $redirect = url('__auth/'.str()->slug($name).'/callback');

                config(['services.'.$name => [
                    'client_id' => $id,
                    'client_secret' => $secret,
                    'redirect' => $redirect,
                ]]);
            }

            // digital ocean spaces
            config(['filesystems.disks.do' => [
                'driver' => 's3',
                'key' => settings('do_spaces_key'),
                'secret' => settings('do_spaces_secret'),
                'region' => settings('do_spaces_region'),
                'bucket' => settings('do_spaces_bucket'),
                'folder' => settings('do_spaces_folder'),
                'endpoint' => settings('do_spaces_endpoint'),
                'use_path_style_endpoint' => false,
            ]]);
        }
    }

    // register macros
    public function registerMacros()
    {
        Builder::mixin(new \Jiannius\Atom\Macros\Builder());
        Carbon::mixin(new \Jiannius\Atom\Macros\Carbon());
        ComponentAttributeBag::mixin(new \Jiannius\Atom\Macros\ComponentAttributeBag());
        Request::mixin(new \Jiannius\Atom\Macros\Request());
        Str::mixin(new \Jiannius\Atom\Macros\Str());
        Stringable::mixin(new \Jiannius\Atom\Macros\Stringable());
    }
}