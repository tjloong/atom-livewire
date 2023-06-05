<x-form.select
    :options="($attributes->get('options') ?? currencies())->map(fn($val) => [
        'value' => data_get($val, 'code'),
        'label' => implode(' - ', array_filter([
            data_get($val, 'code'),
            data_get($val, 'symbol'),
        ])),
    ])"
    {{ $attributes->except('options') }}
/>
