@php
    $type = $attributes->get('type');
    $children = $attributes->get('children');
    $parent = $attributes->get('parent');
@endphp

<x-form.select callback="labels"
    :params="compact('type', 'children', 'parent')" 
    {{ $attributes->except(['type', 'children', 'parent']) }}>
    @isset($foot) {{ $foot }}
    @else
        <x-slot:foot icon="add" label="common.label.new"
            x-on:click="Livewire.emit('createLabel', {{ json_encode(compact('type')) }})"></x-slot:foot>
    @endisset
</x-form.select>