<?php

namespace Jiannius\Atom\Services;

use Jiannius\Atom\Atom;

class Options
{
    public $filters;
    public $selected = [];
    public $options = [];

    public function filter($filters)
    {
        $filters = collect($filters);
        $selected = $filters->pull('value');

        $this->filters = $filters;
        $this->selected = (array) $selected;

        return $this;
    }

    public function countries()
    {
        return Atom::country()->map(fn ($country) => [
            'value' => get($country, 'iso_code'),
            'label' => get($country, 'name'),
        ])->toArray();
    }

    public function states()
    {
        $country = get($this->filters, 'country');
        if (!$country) return [];

        $states = Atom::country($country, 'states');

        return collect($states)->map(fn ($state) => [
            'value' => get($state, 'name'),
            'label' => get($state, 'name'),
        ])->toArray();
    }

    public function currencies() : array
    {
        $search = (string) str(get($this->filters, 'search'))->upper();

        return Atom::country()
            ->map(fn ($country) => get($country, 'currency'))
            ->filter(fn ($val) => !empty(get($val, 'code')) && (
                ($this->selected && in_array(get($val, 'code'), $this->selected))
                || get($val, 'code') === $search
                || str(get($val, 'code'))->is([$search, $search.'*', '*'.$search])
            ))
            ->map(fn($currency) => [
                'value' => get($currency, 'code'),
                'label' => collect([
                    get($currency, 'code'),
                    get($currency, 'symbol'),
                ])->filter()->join(' - '),
            ])
            ->unique('value')
            ->sortBy('label')
            ->values()
            ->all();
    }
}