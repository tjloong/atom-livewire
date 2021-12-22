<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div x-data="inputCurrency($wire.get('{{ $attributes->wire('model')->value() }}'))">
        <div {{ $attributes->whereStartsWith('wire') }}>
            <input type="text" x-model="value" x-init="$watch('value', val => $dispatch('input', val))" class="hidden">
        </div>

        <div class="relative">
            <div class="absolute top-0 bottom-0 left-0 px-4 flex items-center justify-center text-gray-400">
                {{ $attributes->get('currency') }}
            </div>
    
            <input 
                type="text"
                class="form-input w-full pl-14"
                x-bind:value="formatted"
                x-on:input="parseValue"
            >
        </div>
    </div>
</x-input.field>