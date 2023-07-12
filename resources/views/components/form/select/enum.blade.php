@props([
    'id' => $attributes->get('id', 'enum'),
    'options' => enum($attributes->get('enum'))->map(fn($val) => $val->option())->toArray(),
])

<x-form.select :id="$id"
    :options="$options"
    {{ $attributes->except('id', 'options') }}
/>