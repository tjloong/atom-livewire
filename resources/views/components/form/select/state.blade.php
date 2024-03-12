@php
    $label = $attributes->get('label', 'app.label.state');
    $placeholder = $attributes->get('placeholder', 'app.label.select-state');
    $params = [
        'country' => $attributes->get('country'),
    ];
@endphp

<x-form.select :label="$label" :placeholder="$placeholder" callback="states" :params="$params" {{ $attributes->except([
    'label', 'placeholder', 'params', 'country',
]) }}/>