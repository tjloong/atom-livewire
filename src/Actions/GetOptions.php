<?php

namespace Jiannius\Atom\Actions;

class GetOptions
{
    public $name;
    public $filters;
    public $selected = [];
    public $options = [];

    public function __construct($params)
    {
        $this->name = get($params, 'name');

        $filters = collect(get($params, 'filters'));
        $selected = $filters->pull('value');

        $this->filters = $filters->toArray();
        $this->selected = (array) $selected;
    }

    public function run()
    {
        if (str($this->name)->startsWith('enum.')) return $this->getEnums();
        else if (str($this->name)->startsWith(['label', 'labels'])) return $this->getLabels();
        else if (method_exists($this, $this->name)) return $this->{$this->name}();
        else return $this->getFromJson();
    }

    public function countries()
    {
        return collect($this->getFromJson('myinvois.country_codes'))
            ->map(fn ($val) => get($val, 'Country'))
            ->map(fn ($val) => [
                'value' => $val,
                'label' => (string) str($val)->lower()->headline(),
            ])
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function states()
    {
        return collect($this->getFromJson('myinvois.state_codes'))
            ->map(fn ($val) => get($val, 'State'))
            ->filter(fn ($val) => $val !== 'Not Applicable')
            ->values()
            ->map(fn ($val) => [
                'value' => $val,
                'label' => $val,
            ])
            ->toArray();
    }

    public function currencies() : array
    {
        return collect($this->getFromJson('myinvois.currency_codes'))
            ->map(fn ($val) => [
                'value' => get($val, 'Code'),
                'label' => get($val, 'Currency'),
            ])
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function getEnums() : array
    {
        $name = (string) str($this->name)->replaceFirst('enum.', '');
        
        return enum($name)->all()
            ->map(fn ($val) => $val->option())
            ->toArray();
    }

    public function getLabels() : array
    {
        $name = (string) str($this->name)->replaceFirst('labels.', '')->replaceFirst('label.', '');

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

    public function getFromJson($name = null)
    {
        $name = $name ?? $this->name;
        $options = cache('_options');

        if (!$options) {
            $path = resource_path('json/options.json');
            $local = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
            $atom = json_decode(file_get_contents(atom_path('resources/json/options.json')), true);
            $options = array_merge_recursive($atom, $local);
        }

        return get($options, $name);
    }
}
