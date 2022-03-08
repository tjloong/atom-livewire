<?php

namespace Jiannius\Atom\Services;

class Metadata
{
    /**
     * Get countries
     */
    public function countries($code = null) {
        $path = __DIR__.'/../../resources/json/countries.json';
        $json = json_decode(file_get_contents($path));
        $countries = collect($json)->map(fn($country) => array_merge(
            (array)$country, [
                'states' => collect($country->states ?? [])->map(fn($state) => (array)$state)->values()->all(),
            ]
        ));

        if ($code) return $countries->where('iso_code', $code)->first() ?? $countries->where('name', $code)->first();

        return $countries;
    }

    /**
     * Get states for country
     */
    public function states($country)
    {
        $states = optional($this->countries($country))['states'] ?? [];

        return collect($states)->sortBy('code')->values()->all();
    }
}