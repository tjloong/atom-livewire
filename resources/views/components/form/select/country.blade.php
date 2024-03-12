@php
    $label = $attributes->get('label', 'app.label.country');
    $placeholder = $attributes->get('placeholder', 'app.label.select-country');
    $options = countries()->map(fn($country) => [
        'value' => data_get($country, 'code'),
        'label' => data_get($country, 'name'),
        'flag' => data_get($country, 'flag'),
    ])->values()->all();
@endphp

<x-form.select :label="$label" :placeholder="$placeholder" :options="$options" {{ $attributes->except([
    'label', 'placeholder', 'options',
]) }}/>
