<?php

namespace Jiannius\Atom\Services;

class Html
{
    public $config = [];

    public function __call($name, $args)
    {
        return $this->setConfig($name, ...$args);
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;

        return $this;
    }

    public function favicon()
    {
        $favicon = collect([
            ['mime' => 'image/png', 'file' => 'favicon.png'],
            ['mime' => 'image/x-icon', 'file' => 'favicon.ico'],
            ['mime' => 'image/jpeg', 'file' => 'favicon.jpg'],
            ['mime' => 'image/svg+xml', 'file' => 'favicon.svg'],
        ])->first(fn($val) => file_exists(storage_path('app/public/img/'.get($val, 'file'))));

        return $favicon ? (object) [
            ...$favicon,
            'url' => url('storage/img/'.get($favicon, 'file')),
        ] : null;
    }

    public function gfonts()
    {
        return str(app()->currentLocale())->is('zh*')
            ? 'Noto+Sans+SC:wght@100;300;400;500;700;900'
            : 'Inter:wght@100;300;400;500;700;900';
    }

    public function cdn($name)
    {
        $libs = [
            'fontawesome' => [
                "https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/js/all.min.js",
                "https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/fontawesome.min.css",
            ],
            'ckeditor' => [
                atom_path('resources/ckeditor/build/ckeditor.js'),
            ],
            'flatpickr' => [
                'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js',
                'https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css',
            ],
            'shuffle' => [
                'https://cdn.jsdelivr.net/npm/shufflejs@6.1.0/dist/shuffle.min.js',
            ],
            'keen-slider' => [
                'https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.js',
                'https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.css',
            ],
            'fullcalendar' => [
                'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js',
            ],
            'fullcalendar/google-calendar' => [
                'https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js',
            ],
            'animate' => [
                'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
            ],
            'apexcharts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js',
                'https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.css',
            ],
        ];

        $this->config['cdn'] ??= [];

        foreach ($name as $val) {
            $scripts = $libs[$val];

            foreach ($scripts as $script) {
                $this->config['cdn'][] = $script;
            }
        }

        return $this;
    }

    public function get()
    {
        $title = collect([
            !app()->environment('production') ? '['.app()->environment().']' : null,
            config('atom.meta_title') ?? settings('meta_title') ?? config('app.name') ?? '',
        ])->filter()->join(' ');

        $jsonld = [
            '@context' => 'http://schema.org',
            '@type' => 'Website',
            'url' => url()->current(),
            'name' => $title,
        ];

        $config = (object) [
            'lang' => (string) str(app()->currentLocale())->replace('_', '-'),
            'title' => $title,
            'description' => strip_tags(config('atom.meta_description') ?? settings('meta_description') ?? ''),
            'image' => config('atom.meta_image') ?? settings('meta_image') ?? '',
            'hreflang' => config('atom.hreflang'),
            'canonical' => config('atom.canonical'),
            'jsonld' => config('atom.jsonld') ?? $jsonld,
            'favicon' => $this->favicon(),
            'gfonts' => $this->gfonts(),
            'gtm' => env('GOOGLE_TAG_MANAGER_ID'),
            'ga' => env('GOOGLE_ANALYTICS_ID'),
            'fbp' => env('FACEBOOK_PIXEL_ID'),
            'recaptcha' => env('RECAPTCHA_SITE_KEY'),
            'cdn' => [],
            ...$this->config,
        ];

        if ($config->analytics === false) {
            $config->gtm = null;
            $config->ga = null;
            $config->fbp = null;
        }

        return $config;
    }
}