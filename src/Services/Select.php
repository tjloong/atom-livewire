<?php

namespace Jiannius\Atom\Services;

class Select
{
    public $filters;
    public $selected;

    // set static options
    public function static() : array
    {
        return [
            'countries' => $this->countries(),
        ];
    }

    // set filters
    public function filters($filters) : mixed
    {
        $this->filters = $filters;

        return $this;
    }

    // set selected
    public function selected($selected) : mixed
    {
        $this->selected = (array) $selected;

        return $this;
    }

    // get options
    public function get($name) : array
    {
        if (str($name)->is('enum.*')) return $this->enum($name);
        elseif (str($name)->is('labels.*')) return $this->labels($name);

        return $this->$name();
    }

    // get static options
    public function getStatic($name) : array
    {
        return get($this->static(), $name, []);
    }

    // enum
    public function enum($name) : array
    {
        $name = (string) str($name)->replace('enum.', '');
        $exclude = get($this->filters, 'exclude');
        $options = enum($name)->all();

        if ($exclude) $options = $options->reject(fn($case) => in_array($case->name, (array) $exclude))->values();

        return $options->map(fn($case) => $case->option())->toArray();
    }

    // labels
    public function labels($name) : array
    {
        $name = (string) str($name)->replace('labels.', '');

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
            'label' => $label->name_locale,
            'badge' => [
                'color' => $label->color,
                'label' => $label->name_locale,
            ],
        ])->toArray();
    }

    // countries
    public function countries() : array
    {
        return countries()->map(fn($country) => [
            'value' => get($country, 'code'),
            'label' => get($country, 'name'),
            'flag' => get($country, 'flag'),
        ])->values()->all();
    }
}
