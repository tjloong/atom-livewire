@php
    $label = $attributes->get('label', 'app.label.state');
    $placeholder = $attributes->get('placeholder', 'app.label.select-state');
    $country = $attributes->get('country');
@endphp

<x-form.select callback="states"
    :params="['country' => $country]"
    :label="$label"
    :placeholder="$placeholder"
    {{ $attributes->except([
        'label', 'placeholder', 'country',
    ]) }}/>