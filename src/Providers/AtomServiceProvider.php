<?php

namespace Jiannius\Atom\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
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
        // helpers
        require_once __DIR__.'/../Helpers/Core.php';
        require_once __DIR__.'/../Helpers/Atom.php';
        require_once __DIR__.'/../Helpers/Component.php';
        require_once __DIR__.'/../Helpers/Database.php';
        require_once __DIR__.'/../Helpers/Route.php';

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'atom');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'atom');

        $this->bindings();
        $this->macros();
        $this->configs();
        $this->gates();
        $this->blades();
        $this->components();

        // custom polymorphic types
        if ($morphMap = config('atom.morph_map')) {
            Relation::enforceMorphMap($morphMap);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../publishes/app' => base_path('app'),
                __DIR__.'/../../publishes/config' => base_path('config'),
                __DIR__.'/../../publishes/resources' => base_path('resources'),
                __DIR__.'/../../publishes/routes' => base_path('routes'),
                __DIR__.'/../../publishes/tailwind.config.js' => base_path('tailwind.config.js'),
                __DIR__.'/../../publishes/postcss.config.js' => base_path('postcss.config.js'),
                __DIR__.'/../../publishes/vite.config.js' => base_path('vite.config.js'),
            ], 'atom');

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
    }

    // components
    public function components() : void
    {
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

    // blades
    public function blades() : void
    {
        Blade::directive('recaptcha', function() {
            $sitekey = settings('recaptcha_site_key');

            return $sitekey
                ? "<?php echo '<script src=\"https://www.google.com/recaptcha/api.js?render=$sitekey\"></script>' ?>"
                : "";
        });

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

    // gates
    public function gates() : void
    {
        $policy = find_class('policy');

        Gate::define('tier', [$policy, 'tier']);
        Gate::define('role', [$policy, 'role']);
        Gate::define('permission', [$policy, 'permission']);
        Gate::define('perm', [$policy, 'permission']);
    }

    // configs
    public function configs() : void
    {
        if (config('atom.static')) return;

        if ($this->app->runningInConsole()) {
            // basset cache paths
            config(['backpack.basset.view_paths' => [
                resource_path('views'),
                atom_path('resources/views'),
            ]]);
        }
        else {
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

        // smtp
        config(['mail.mailers.smtp' => [
            'host' => settings('smtp_host'),
            'port' => settings('smtp_port'),
            'username' => settings('smtp_username'),
            'password' => settings('smtp_password'),
            'encryption' => settings('smtp_encryption'),
        ]]);

        // mailgun
        config(['services.mailgun' => [
            'domain' => settings('mailgun_domain'),
            'secret' => settings('mailgun_secret'),
        ]]);

        // default mailer
        config([
            'mail.default' => settings('mailer'),
            'mail.from.address' => settings('notify_from'),
            'mail.from.name' => config('app.name'),
        ]);
    }

    // macros
    public function macros() : void
    {
        if (!Request::hasMacro('portal')) {
            Request::macro('portal', function($is = null) {
                $route = $this->route()?->getName();

                if (in_array($route, ['login', 'logout', 'register', 'password.forgot', 'password.reset'])) {
                    $portal = 'auth';
                }
                else if ($route) {
                    $portal = collect(explode('.', $route))->first();
                    if (str($portal)->startsWith('__') || in_array($portal, ['socialite'])) $portal = null;
                }

                if ($is && $portal) return $portal === $is;

                return $portal;
            });
        }

        if (!ComponentAttributeBag::hasMacro('hasLike')) {
            ComponentAttributeBag::macro('hasLike', function() {
                $value = func_get_args();
                $keys = collect($this->getAttributes())->keys();

                return !empty(
                    $keys->first(fn($key) => str($key)->is($value))
                );
            });
        }

        if (!ComponentAttributeBag::hasMacro('modifiers')) {
            ComponentAttributeBag::macro('modifier', function($name = null) {
                $attribute = collect($this->whereStartsWith('wire:model')->getAttributes())->keys()->first()
                    ?? collect($this->whereStartsWith('x-model')->getAttributes())->keys()->first();

                $modifier = (string) str($attribute)->replace('x-model', '')->replace('wire:model', '');

                return $name ? str($modifier)->is('*'.$name.'*') : $modifier;
            });
        }

        if (!Carbon::hasMacro('local')) {
            Carbon::macro('local', function() {
                $tz = optional(user())->settings('timezone') ?? config('atom.timezone');
                return $tz ? $this->timezone($tz) : $this;
            });
        }

        if (!Carbon::hasMacro('pretty')) {
            Carbon::macro('pretty', function($option = 'date') {
                if ($option === 'date') $format = 'd M Y';
                if ($option === 'datetime') $format = 'd M Y g:iA';
                if ($option === 'datetime-24') $format = 'd M Y H:i:s';
                if ($option === 'time') $format = 'g:i A';
                if ($option === 'time-24') $format = 'H:i:s';

                return $this->local()->format($format);
            });
        }
    }

    // bindings
    public function bindings() : void
    {
        $this->app->bind('route', fn() => new \Jiannius\Atom\Services\Route);

        $this->app->bind('image', function() {
            if (extension_loaded('imagick')) return new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Imagick\Driver());
            if (extension_loaded('gd')) return new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        });
    }
}