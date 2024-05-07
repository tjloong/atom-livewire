@php
    $label = $attributes->get('label', 'app.label.currency');
@endphp

<x-form.select callback="currencies" :label="$label" {{ $attributes->except(['label', 'options']) }}/>