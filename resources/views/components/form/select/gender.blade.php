@php
    $label = $attributes->get('label', 'common.label.gender');
    $placeholder = $attributes->get('placeholder', 'common.label.select-gender');
@endphp

<x-form.select callback="gender"
    :label="$label"
    :placeholder="$placeholder"
    {{ $attributes->except(['label', 'placeholder']) }}/>
