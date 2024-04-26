@php
    $name = $attributes->get('enum');
    $exclude = $attributes->get('exclude');
    $options = enum($name)->all();

    if ($exclude) $options = $options->reject(fn($case) => in_array($case->name, (array) $exclude))->values();

    $options = $options->map(fn($case) => $case->option());
@endphp

<x-form.select 
    :options="$options" 
    :searchable="true"
    {{ $attributes->except(['exclude', 'enum', 'options']) }}>
    {{ $slot }}
</x-form.select>