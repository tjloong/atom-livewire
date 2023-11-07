@php
    $name = $attributes->get('enum');
    $exclude = $attributes->get('exclude');
    $options = enum($name)->when($exclude, fn($enums) => 
        $enums->reject(fn($enum) => in_array($enum->name, (array) $exclude))->values()
    )->map(fn($val) => $val->option())->values()->all();
@endphp

<x-form.select :options="$options" :search="false"
    {{ $attributes->except('options') }}/>