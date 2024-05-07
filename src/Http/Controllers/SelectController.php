<?php

namespace Jiannius\Atom\Http\Controllers;

use App\Http\Controllers\Controller;

class SelectController extends Controller
{
    public $options;

    // get
    public function get() : mixed
    {
        $callback = request()->input('callback');
        $params = request()->input('params');
        $value = request()->input('value');

        $this->$callback($params, $value);

        return $this->options ?? [];
    }

    // set option
    public function setOption($opt) : void
    {
        if (!$this->options) $this->options = collect();

        $this->options->push($opt);
    }

    // dial codes
    public function dial_codes() : void
    {
        countries()->each(fn($val) => $this->setOption([
            'value' => get($val, 'dial_code'),
            'label' => get($val, 'name'),
            'flag' => get($val, 'flag'),
        ]));
    }

    // states
    public function states($params, $value) : void
    {
        if ($country = data_get($params, 'country')) {
            collect(countries($country.'.states'))
                ->sortBy('name')
                ->each(fn($opt) => $this->setOption([
                    'value' => data_get($opt, 'name'),
                    'label' => data_get($opt, 'name')
                ]));
        }
    }

    // currencies
    public function currencies($params, $value) : void
    {
        $search = (string) str(get($params, 'search'))->upper();

        currencies()
        ->filter(fn($val) => !empty(get($val, 'code')) && (
            get($val, 'code') === $value
            || get($val, 'code') === $search
            || str(get($val, 'code'))->is([$search, $search.'*', '*'.$search])
        ))
        ->map(fn($currency) => [
            'value' => data_get($currency, 'code'),
            'label' => collect([
                data_get($currency, 'code'),
                data_get($currency, 'symbol'),
            ])->filter()->join(' - '),
        ])
        ->unique('value')
        ->sortBy('label')
        ->values()
        ->each(fn($opt) => $this->setOption($opt));
    }

    // labels
    public function labels($params, $value) : void
    {
        $type = data_get($params, 'type');
        $search = data_get($params, 'search');
        $parentId = data_get($params, 'parent');
        $loadChildren = data_get($params, 'children');

        $labels = model('label')->whereIn('id', (array) $value)->union(
            model('label')
            ->whereNotIn('id', (array) $value)
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($parentId, 
                fn($q) => $q->whereIn('parent_id', (array) $parentId),
                fn($q) => $q->whereNull('parent_id'),
            )
            ->when($search, fn($q) => $q->search($search))
        )->orderBy('seq')->orderBy('id')->get();

        foreach ($labels as $label) {
            $path = $loadChildren
                ? $label->parents
                    ->map(fn($parent) => $parent->locale('name'))
                    ->map(fn($val) => str($val)->limit(15)->toString())
                    ->join(' / ')
                : null;

            $this->setOption([
                'value' => $label->id,
                'label' => collect([$path, $label->locale('name')])->filter()->join(' / '),
                'color' => $label->color,
            ]);

            if ($loadChildren && $label->children->count()) {
                $this->labels(array_merge($params, ['parent' => $label->id]), $value);
            }
        }
    }
}