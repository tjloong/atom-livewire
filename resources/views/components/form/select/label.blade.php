@php
    $label = $attributes->get('label', 'app.label.label');
    $placeholder = $attributes->get('placeholder', 'app.label.select-label');
    $type = $attributes->get('type');
    $children = $attributes->get('children');
    $parent = $attributes->get('parent');
@endphp

<x-form.select callback="labels"
    :label="$label"
    :placeholder="$placeholder"
    :params="compact('type', 'children', 'parent')" 
    {{ $attributes->except(['label', 'placeholder', 'type', 'children', 'parent']) }}>
    @isset($foot) {{ $foot }}
    @else
        <x-slot:foot icon="add" label="app.label.new"
            x-on:click="Livewire.emit('createLabel', {{ json_encode(compact('type')) }})"></x-slot:foot>
    @endisset
</x-form.select>