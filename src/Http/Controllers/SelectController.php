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

    // currencies
    public function currencies() : void
    {
        currencies()
            ->reject(fn($val) => empty(data_get($val, 'code')))
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
                fn($q) => $q->where('parent_id', $parentId),
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

    // months
    public function months() : void
    {
        collect(range(1, 12))->map(fn($n) => $this->setOption([
            'value' => $n,
            'label' => date('F', mktime(0, 0, 0, $n, 1, 2000)),
        ]));
    }

    // month days
    public function month_days($params) : void
    {
        $month = data_get($params, 'month');

        collect($month
            ? range(1, cal_days_in_month(CAL_GREGORIAN, (int) $month, 2000))
            : range(1, 31)
        )->each(fn($n) => $this->setOption([
            'value' => $n,
            'label' => (string) $n,
        ]));
    }
}