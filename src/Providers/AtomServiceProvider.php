<?php

namespace Jiannius\Atom\Providers;

use Carbon\CarbonImmutable;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;

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
        $this->registerRoutes();
        $this->registerHelpers();
        $this->registerViews();
        $this->registerTranslation();
        $this->registerMigrations();
        $this->registerBindings();
        $this->registerConfigs();
        $this->registerBladeIfs();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
        $this->registerServices();
        $this->registerTagCompiler();
        $this->registerMorphMap();
        $this->registerMacros();
        $this->registerLivewire();
        $this->registerPublishes();
        $this->registerCommands();
        $this->configureDate();
    }

    public function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/channels.php');
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

    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
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

        // digital ocean spaces
        if (env('FILESYSTEM_DISK') === 'do') {
            config(['filesystems.disks.do' => [
                'driver' => 's3',
                'key' => env('DO_SPACES_KEY'),
                'secret' => env('DO_SPACES_SECRET'),
                'region' => env('DO_SPACES_REGION'),
                'bucket' => env('DO_SPACES_BUCKET'),
                'folder' => env('DO_SPACES_FOLDER'),
                'endpoint' => env('DO_SPACES_ENDPOINT'),
                'use_path_style_endpoint' => false,
            ]]);
        }
    }

    // register macros
    public function registerMacros()
    {
        Builder::mixin(new \Jiannius\Atom\Macros\Builder());
        ComponentAttributeBag::mixin(new \Jiannius\Atom\Macros\ComponentAttributeBag());
        Request::mixin(new \Jiannius\Atom\Macros\Request());
        Str::mixin(new \Jiannius\Atom\Macros\Str());
        Stringable::mixin(new \Jiannius\Atom\Macros\Stringable());
    }

    public function registerLivewire()
    {
        Livewire::component('atom.auth.login', \Jiannius\Atom\Livewire\Auth\Login::class);
        Livewire::component('atom.auth.logout', \Jiannius\Atom\Livewire\Auth\Logout::class);
        Livewire::component('atom.auth.register', \Jiannius\Atom\Livewire\Auth\Register::class);
        Livewire::component('atom.auth.reset-password', \Jiannius\Atom\Livewire\Auth\ResetPassword::class);
        Livewire::component('atom.auth.forgot-password', \Jiannius\Atom\Livewire\Auth\ForgotPassword::class);

        Livewire::component('atom.user.listing', \Jiannius\Atom\Livewire\User\Listing::class);
        Livewire::component('atom.user.edit', \Jiannius\Atom\Livewire\User\Edit::class);

        Livewire::component('atom.enquiry', \Jiannius\Atom\Livewire\Enquiry::class);
        Livewire::component('atom.profile', \Jiannius\Atom\Livewire\Profile::class);
        Livewire::component('atom.footprint', \Jiannius\Atom\Livewire\Footprint::class);
        Livewire::component('atom.generic-page', \Jiannius\Atom\Livewire\GenericPage::class);
        Livewire::component('atom.site-settings', \Jiannius\Atom\Livewire\SiteSettings::class);
        Livewire::component('atom.notification-center', \Jiannius\Atom\Livewire\NotificationCenter::class);
    }

    public function configureDate()
    {
        Date::use(\Jiannius\Atom\Services\Carbon::class);
    }
}
