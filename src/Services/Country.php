<?php

namespace Jiannius\Atom\Services;

class Country
{
    public function all()
    {
        $path = collect([
            resource_path('json/countries.json'),
            __DIR__.'/../../resources/json/countries.json',
        ])->first(fn($val) => file_exists($val));

        $json = json_decode(file_get_contents($path), true);

        return collect($json);
    }

    public function get($name, $field = null)
    {
        $countries = $this->all();
        $country = $countries->first(fn ($value) => 
            strtolower(get($value, 'name')) == strtolower($name)
            || strtolower(get($value, 'iso_code')) == strtolower($name)
    
        );

        if (!$country) return null;
        if ($field) return get($country, $field);

        return $country;
    }
}