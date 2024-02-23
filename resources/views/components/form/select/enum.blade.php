@php
    $name = $attributes->get('enum');
    $exclude = $attributes->get('exclude');

    $options = $exclude
        ? enum($name)->all(false)->reject(fn($case) => in_array($$case->name, (array) $exclude))->values()
        : enum($name)->all();

    $options = $options->map(fn($case) => $case->option());
@endphp

<x-form.select :options="$options" :search="false" {{ $attributes->except(['exclude', 'enum', 'options']) }}/>