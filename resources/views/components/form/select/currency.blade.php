@props([
    'id' => $attributes->get('id', 'currency'),
    'label' => $attributes->get('label', 'Currency'),
    'options' => $attributes->get('options'),
])

<x-form.select :id="$id"
    :label="$label"
    :options="collect($options ?? currencies())->map(fn($val) => [
        'value' => data_get($val, 'code'),
        'label' => implode(' - ', array_filter([
            data_get($val, 'code'),
            data_get($val, 'symbol'),
        ])),
    ])"
    {{ $attributes->except('id', 'label', 'options') }}
/>
