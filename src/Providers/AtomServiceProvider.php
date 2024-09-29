<?php

namespace Jiannius\Atom\Providers;

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
        $this->registerTagCompiler();
        $this->registerMorphMap();
        $this->registerRequestMacros();
        $this->registerComponentMacros();
        $this->registerStringMacros();
        $this->registerCarbonMacros();
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

        \Jiannius\Atom\Services\Alert::boot();
        \Jiannius\Atom\Services\Modal::boot();
        \Jiannius\Atom\Services\Toast::boot();
        \Jiannius\Atom\Services\Confirm::boot();
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

            // smtp
            config(['mail.mailers.smtp' => [
                'transport' => 'smtp',
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
    }

    // register request macros
    public function registerRequestMacros()
    {
        if (!Request::hasMacro('portal')) {
            Request::macro('portal', function ($is = null) {
                $route = $this->route()?->getName();

                if (in_array($route, ['login', 'logout', 'register', 'password.forgot', 'password.reset'])) {
                    $portal = 'auth';
                }
                else if ($route) {
                    $portal = collect(explode('.', $route))->first();
                    if (str($portal)->startsWith('__') || in_array($portal, ['socialite'])) $portal = null;
                }
                else $portal = null;

                if ($is && $portal) return $portal === $is;

                return $portal;
            });
        }

        if (!Request::hasMacro('subdomain')) {
            Request::macro('subdomain', function () {
                $segments = collect(explode('.', $this->host()));
                $segments->pop(2);

                return $segments->join('.') ?: null;
            });
        }

        if (!Request::hasMacro('hostWithoutSubdomain')) {
            Request::macro('hostWithoutSubdomain', function () {
                return collect(explode('.', $this->host()))->sortKeysDesc()->take(2)->sortKeys()->join('.');
            });
        }

        if (!Request::hasMacro('isLivewireRequest')) {
            Request::macro('isLivewireRequest', function () {
                return app(\Livewire\LivewireManager::class)->isLivewireRequest();
            });
        }
    }

    // register component macros
    public function registerComponentMacros()
    {
        if (!ComponentAttributeBag::hasMacro('hasLike')) {
            ComponentAttributeBag::macro('hasLike', function() {
                $value = func_get_args();
                $keys = collect($this->getAttributes())->keys();

                return !empty(
                    $keys->first(fn($key) => str($key)->is($value))
                );
            });
        }

        if (!ComponentAttributeBag::hasMacro('modifier')) {
            ComponentAttributeBag::macro('modifier', function($name = null) {
                $attribute = collect($this->whereStartsWith('wire:model')->getAttributes())->keys()->first()
                    ?? collect($this->whereStartsWith('x-model')->getAttributes())->keys()->first();

                $modifier = (string) str($attribute)->replace('x-model', '')->replace('wire:model', '');

                return $name ? str($modifier)->is('*'.$name.'*') : $modifier;
            });
        }

        if (!ComponentAttributeBag::hasMacro('size')) {
            ComponentAttributeBag::macro('size', function($default = null) {
                return $this->get('size') ?? pick([
                    '2xs' => $this->has('2xs'),
                    'xs' => $this->has('xs'),
                    'sm' => $this->has('sm'),
                    'md' => $this->has('md'),
                    'lg' => $this->has('lg'),
                    'xl' => $this->has('xl'),
                    '2xl' => $this->has('2xl'),
                    '3xl' => $this->has('3xl'),
                    '4xl' => $this->has('4xl'),
                ]) ?? $default;
            });
        }

        if (!ComponentAttributeBag::hasMacro('field')) {
            ComponentAttributeBag::macro('field', function() {
                return $this->get('field') ?? $this->get('for') ?? $this->wire('model')->value();
            });
        }

        // TODO: deprecate this
        if (!ComponentAttributeBag::hasMacro('submitAction')) {
            ComponentAttributeBag::macro('submitAction', function() {
                if ($this->hasLike('wire:submit*', 'x-on:submit*', 'x-recaptcha:submit*')) return true;
                if (is_string($this->get('submit'))) return $this->get('submit');
                if (is_string($this->get('form'))) return $this->get('form');
                if ($this->has('submit') || $this->has('form')) return 'submit';

                return false;
            });
        }

        if (!ComponentAttributeBag::hasMacro('submit')) {
            ComponentAttributeBag::macro('submit', function() {
                $attrs = collect(
                    $this->filter(fn ($value, $key) => 
                        str($key)->is('wire:submit*')
                        || str($key)->is('x-on:submit*')
                        || str($key)->is('x-recaptcha:submit*')
                    )->getAttributes()
                );

                $attr = $attrs->keys()->first();
                $value = $attrs->values()->first();

                return $attr ? (object) compact('attr', 'value') : false;
            });
        }

        if (!ComponentAttributeBag::hasMacro('getAny')) {
            ComponentAttributeBag::macro('getAny', function(...$args) {
                return collect($args)->map(fn($arg) => $this->get($arg))->filter()->first();
            });
        }

        if (!ComponentAttributeBag::hasMacro('classes')) {
            ComponentAttributeBag::macro('classes', function () {
                return new class
                {
                    public $pending = [];

                    public function add($classes)
                    {
                        $this->pending[] = $classes;
                        return $this;
                    }

                    public function __toString()
                    {
                        return collect($this->pending)->join(' ');
                    }
                };
            });
        }

        if (!ComponentAttributeBag::hasMacro('styles')) {
            ComponentAttributeBag::macro('styles', function () {
                return new class
                {
                    public $pending = [];

                    public function add($prop, $value)
                    {
                        $this->pending[$prop] = $value;
                        return $this;
                    }

                    public function __toString()
                    {
                        return collect($this->pending)->map(fn($value, $prop) => "$prop: $value")->join('; ');
                    }
                };
            });
        }
    }

    // register carbon macros
    public function registerCarbonMacros()
    {
        if (!Carbon::hasMacro('local')) {
            Carbon::macro('local', function () {
                $tz = optional(user())->settings('timezone') ?? config('atom.timezone');
                return $tz ? $this->timezone($tz) : $this;
            });
        }

        if (!Carbon::hasMacro('pretty')) {
            Carbon::macro('pretty', function ($option = null) {
                $option = $option ?? 'date';

                if ($option === 'date') $format = 'd M Y';
                elseif ($option === 'datetime') $format = 'd M Y g:iA';
                elseif ($option === 'datetime-24') $format = 'd M Y H:i:s';
                elseif ($option === 'time') $format = 'g:i A';
                elseif ($option === 'time-24') $format = 'H:i:s';
                else $format = $option;

                return $this->local()->format($format);
            });
        }

        if (!Carbon::hasMacro('recent')) {
            Carbon::macro('recent', function ($days = 1) {
                if ($this->isToday()) return $this->pretty('time');
                if ($this->gte(now()->subDays($days))) return $this->local()->fromNow();

                return $this->pretty('datetime');
            });
        }
    }

    // register string macros
    public function registerStringMacros()
    {
        if (!Str::hasMacro('interval')) {
            Str::macro('interval', function($string) {
                $count = trim(head(explode(' ', $string)));
                $interval = trim(last(explode(' ', $string)));
                $interval = pick([
                    'day' => in_array($interval, ['day', 'days']),
                    'week' => in_array($interval, ['week', 'weeks']),
                    'month' => in_array($interval, ['month', 'months']),
                    'year' => in_array($interval, ['year', 'years']),
                ]);

                if ($count == 1 && $interval === 'day') return tr('app.label.daily');
                if ($count == 1 && $interval === 'month') return tr('app.label.monthly');
                if ($count == 3 && $interval === 'month') return tr('app.label.quarterly');
                if ($count == 6 && $interval === 'month') return tr('app.label.half-yearly');
                if ($count == 1 && $interval === 'week') return tr('app.label.weekly');
                if ($count == 1 && $interval === 'year') return tr('app.label.yearly');

                if ($interval === 'day') return tr('app.label.day-count', $count);
                if ($interval === 'week') return tr('app.label.week-count', $count);
                if ($interval === 'month') return tr('app.label.month-count', $count);
                if ($interval === 'year') return tr('app.label.year-count', $count);
            });

            Stringable::macro('interval', function (string $delimiter = '') {
                return new Stringable (Str::interval($this->value));
            });
        }
    }
}