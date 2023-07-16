<x-form.select
    :options="$options"
    {{ $attributes->except(['type', 'options', 'id', 'children']) }}
>
    @isset($foot) {{ $foot }}
    @else
        <x-slot:foot icon="add"
            :label="'New '.component_label($attributes, 'Label')"
            wire:click="$emitTo('{{ atom_lw('app.label.form') }}', 'open', { type: '{{ $type }}' })"
        ></x-slot:foot>
    @endisset
</x-form.select>