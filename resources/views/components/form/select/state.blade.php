@props([
    'options' => metadata()
        ->states($attributes->get('country'))
        ->map(fn($val) => data_get($val, 'name'))
        ->values()
        ->all(),
])

<x-form.select
    :label="$attributes->get('label', 'State')"
    :options="$options"
    {{ $attributes->except(['label', 'options']) }}
/>
