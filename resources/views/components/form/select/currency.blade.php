@php
    $label = $attributes->get('label', 'common.label.currency');
@endphp

<x-form.select callback="currencies" :label="$label" {{ $attributes->except(['label', 'options']) }}/>