<?php

namespace Jiannius\Atom\Actions;

use Illuminate\Support\Facades\Blade;

class GetOptions
{
    public $name;
    public $filters;
    public $selected = [];
    public $options = [];
    public $exclude = [];

    public function __construct($params)
    {
        $this->name = get($params, 'name');

        $filters = collect(get($params, 'filters'));
        $selected = $filters->pull('value');

        $this->filters = $filters->toArray();
        $this->selected = (array) $selected;
        $this->exclude = get($this->filters, 'exclude');
    }

    public function run()
    {
        $options = match (true) {
            str($this->name)->startsWith('enum.') =>  $this->getEnums(),
            str($this->name)->startsWith(['label', 'labels']) =>  $this->getLabels(),
            method_exists($this, $this->name) => $this->{$this->name}(),
            default => $this->getFromJson(),
        };

        return collect($options)->map(function ($option) {
            if (get($option, 'html')) return $option;

            $label = '<div class="text-wrap">'.get($option, 'label').'</div>';
            $caption = get($option, 'caption') ? '<div class="text-muted text-sm text-wrap">'.get($option, 'caption').'</div>' : '';
            $avatar = get($option, 'avatar')
                ? Blade::render('<atom:avatar size="xs" :avatar="$avatar">{{ $name }}</atom:avatar>', ['name' => get($option, 'label'), 'avatar' => get($option, 'avatar')])
                : '';

            return [
                ...$option,
                'html' => $avatar
                    ? <<<EOL
                    <div class="w-full flex items-center gap-2">
                        <div class="shrink-0">
                        {$avatar}
                        </div>
                        <div class="grow">
                        {$label}
                        {$caption}
                        </div>
                    </div>
                    EOL
                    : <<<EOL
                    <div class="w-full">
                        {$label}
                        {$caption}
                    </div>
                    EOL,
            ];
        })->toArray();
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
        $enums = enum($name)->all();

        if ($this->exclude) $enums = $enums->reject(fn ($item) => $item->is($this->exclude))->values();
   
        return $enums->map(fn ($val) => $val->option())->toArray();
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
