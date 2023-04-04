<x-form.select
    :label="$attributes->get('label', 'State')"
    :options="collect(countries($attributes->get('country').'.states'))
        ->map(fn($val) => data_get($val, 'name'))
        ->sort()
        ->toArray()
    "
    {{ $attributes->except(['label', 'options']) }}
/>
