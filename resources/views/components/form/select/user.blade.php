@props([
    'id' => $attributes->get('id', 'user'),
    'label' => $attributes->get('label', 'User'),
    'options' => $attributes->get('options'),
])

<x-form.select :id="$id"
    :label="$label"
    :options="$options ?? model('user')->readable()->get()->toArray()"
    {{ $attributes->except(['label', 'options']) }}
/>
