@props([
    'country' => $attributes->get('country'),
])

<x-form.select
    :label="$attributes->get('label', 'State')"
    :options="
        $country
            ? collect(countries($country.'.states'))
                ->map(fn($val) => [
                    'value' => data_get($val, 'name'),
                    'label' => data_get($val, 'name')
                ])
                ->sort()
                ->toArray()
            : []
    "
    {{ $attributes->except(['label', 'options']) }}
/>
