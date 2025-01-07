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

    public function countries() : array
    {
        return collect($this->getFromJson('countries'))
            ->map(fn ($item) => [
                'value' => get($item, 'iso_code'),
                'label' => get($item, 'name'),
            ])
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function states() : array
    {
        $countries = collect($this->getFromJson('countries'));
        $country = $countries->firstWhere('iso_code', get($this->filters, 'country') ?? 'MY');
        $states = get($country, 'states');

        return collect($states)
            ->map(fn ($item) => [
                'value' => get($item, 'name'),
                'label' => get($item, 'name'),
            ])
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function dialcodes() : array
    {
        return collect($this->getFromJson('countries'))
            ->map(fn ($item) => [
                'value' => get($item, 'dial_code'),
                'label' => get($item, 'iso_code').' ('.get($item, 'dial_code').')',
            ])
            ->sortBy('label')
            ->values()
            ->toArray();
    }

    public function currencies() : array
    {
        $countries = collect($this->getFromJson('countries'));

        return $countries
            ->map(fn ($item) => [
                'value' => get($item, 'currency.code'),
                'label' => collect([get($item, 'currency.code'), get($item, 'name')])->filter()->join(' - '),
            ])
            ->filter(fn ($item) => !empty(get($item, 'value')))
            ->values()
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
        $cached = cache('_options') ?? [];
        $options = get($cached, $name);

        if ($options) return $options;

        $path = resource_path('json/options/'.$name.'.json');
        $local = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        $atom = json_decode(file_get_contents(atom_path('resources/json/options/'.$name.'.json')), true);
        $options = array_merge_recursive($atom, $local);
        $cached[$name] = $options;

        cache(['_options' => $cached]);

        return $options;
    }
}
