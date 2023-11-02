@php
    $label = $attributes->get('label', 'common.label.country');
@endphp

<x-form.select :label="$label" callback="countries" {{ $attributes->except('label') }}/>
