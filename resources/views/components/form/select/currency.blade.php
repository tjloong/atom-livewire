<x-form.select
    :options="metadata()->countries()
        ->map(fn($val) => [
            'value' => data_get($val, 'currency.code'),
            'label' => implode(' - ', array_filter([
                data_get($val, 'currency.code'),
                data_get($val, 'currency.symbol'),
            ])),
        ])
        ->filter(fn($val) => !empty(data_get($val, 'value')))
        ->unique('value')
        ->sortBy('value')
        ->values()
    "
    {{ $attributes->except('options') }}
/>