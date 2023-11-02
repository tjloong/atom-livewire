@php
    $name = $attributes->get('enum');
    $exclude = $attributes->get('exclude');
@endphp

<x-form.select callback="enums" 
    :params="compact('name', 'exclude')"
    {{ $attributes->except('options') }}/>