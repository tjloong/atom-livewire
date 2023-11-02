@php
    $label = $attributes->get('label', 'common.label.state');
    $placeholder = $attributes->get('placeholder', 'common.label.select-state');
    $country = $attributes->get('country');
@endphp

<x-form.select callback="states"
    :label="$label"
    :params="compact('country')"
    :placeholder="$placeholder"
    {{ $attributes->except(['label', 'options']) }}/>
