@php
    $label = $attributes->get('label', 'common.label.country');
    $options = countries()->map(fn($country) => [
        'value' => data_get($country, 'code'),
        'label' => data_get($country, 'name'),
        'flag' => data_get($country, 'flag'),
    ])->values()->all();
@endphp

<x-form.select :label="$label" :options="$options" {{ $attributes->except(['label', 'options']) }}/>
