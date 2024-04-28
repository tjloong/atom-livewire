<?php

namespace Jiannius\Atom\Services;

class CDN
{
    // get all liraries
    public function getLibraries() : mixed
    {        
        return cache()->rememberForever('cdn-libraries', function() {
            $libs = collect();
            $atom = json_decode(file_get_contents(atom_path('resources/json/cdn.json')), true);
            $app = ($path = resource_path('json/cdn.json')) && file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];

            foreach ([$atom, $app] as $items) {
                foreach ($items as $name => $val) {
                    foreach ($this->getSources($val) as $src) {
                        $url = is_string($src) ? $src : get($src, 'url');
                        $attr = is_string($src) ? [] : collect($src)->except('url')->toArray();
                        $extension = (string) str($url)->afterLast('.');

                        if (!str($url)->startsWith(['http', 'https'])) {
                            $url = atom_path($url);
                        }

                        $libs->push(compact('name', 'url', 'attr'));
                    }
                }
            }

            return $libs->reject(fn($lib) => empty(get($lib, 'url')))->values();
        });
    }

    // get sources
    public function getSources($parameters) : array
    {
        if (is_string($parameters)) return [$parameters];
        if (isset($parameters['url'])) return [$parameters];

        return $parameters;
    }

    // get attr
    public function getAttr($attr) : string
    {
        $str = '';

        foreach ($attr as $key => $value) {
            if (is_string($value)) $value = "'$value'";
            if (is_bool($value)) $value = $value ? 'true' : 'false';
            $str .= "'$key'".' => '.$value.',';
        }

        $str = (string) str($str)->replaceLast(',', '');

        return "[$str]";
    }

    // get cdn
    public function get($name) : array
    {
        if ($name === 'recaptcha' && ($sitekey = settings('recaptcha_site_key'))) {
            return [['url' => '<script src="https://www.google.com/recaptcha/api.js?render='.$sitekey.'"></script>']];
        }

        return $this->getLibraries()->where('name', $name)->values()->all();
    }
}