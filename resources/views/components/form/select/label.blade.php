<x-form.select
    :options="$options"
    {{ $attributes->except(['type', 'options', 'id', 'children']) }}>
    @isset($foot) {{ $foot }}
    @else
        <x-slot:foot icon="add" label="common.label.new"
            wire:click="$emit('createLabel', {{ json_encode(compact('type')) }})"></x-slot:foot>
    @endisset
</x-form.select>