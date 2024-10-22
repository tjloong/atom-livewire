<?php

namespace Jiannius\Atom\Services;

use Jiannius\Atom\Atom;

class Options
{
    public $filters;
    public $selected = [];
    public $options = [];

    public function __call($name, $arguments)
    {
        if (str($name)->startsWith('enum.')) return $this->getEnums($name);
        elseif (str($name)->startsWith(['label', 'labels'])) return $this->getLabels($name);
    }

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

    public function getEnums($name) : array
    {
        $name = (string) str($name)->replaceFirst('enum.', '');
        
        return enum($name)->all()
            ->map(fn ($val) => $val->option())
            ->toArray();
    }

    public function getLabels($name) : array
    {
        $name = (string) str($name)->replaceFirst('labels.', '')->replaceFirst('label.', '');

        return model('label')->whereIn('id', $this->selected)->union(
            model('label')
                ->whereNotIn('id', $this->selected)
                ->where('type', $name)
                ->when(get($this->filters, 'parent_id'),
                    fn($q, $id) => $q->whereIn('parent_id', (array) $id),
                    fn($q) => $q->whereNull('parent_id'),
                )
                ->when(get($this->filters, 'search'), fn($q, $search) => $q->search($search))
        )->sequence()->get()->map(fn($label) => [
            'value' => $label->id,
            'label' => (string) $label,
            'color' => $label->color,
        ])->toArray();
    }
}