@php
    $placeholder = $attributes->get('placeholder', 'Write something...');
@endphp

<x-form.field {{ $attributes }}>
    <div wire:ignore
        x-cloak
        x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value() }}').defer, @js($placeholder))"
        x-init="() => init($refs.editor)"
        x-bind:class="focus && 'ring-1 ring-theme'"
        class="editor relative bg-white border border-gray-300 rounded-lg"
        {{ $attributes->whereDoesntStartWith('wire:model') }}>
        <div class="sticky -top-5 z-30 flex items-center flex-wrap border-b bg-white m-1">
            <x-form.editor.heading/>
            <x-form.editor.text/>
            <x-form.editor.bullet/>
            <x-form.editor.tools/>
            <x-form.editor.table/>
            <x-form.editor.media/>
            <x-form.editor.actions/>
        </div>

        <div x-ref="editor"></div>
    </div>
</x-form.field>