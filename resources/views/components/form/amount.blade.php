<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div 
        x-data="formAmount(
            @if ($wire = $attributes->wire('model')->value()) $wire.get('{{ $wire }}')
            @elseif ($value = $attributes->get('value')) @js($value)
            @endif
        )"
        class="relative"
    >
        <div {{ $attributes->except('error', 'required', 'caption') }}>
            <input type="number" x-ref="input" x-bind:value="value" step="any" class="hidden">
        </div>

        @if ($attributes->has('prefix'))
            <div class="absolute top-0 bottom-0 left-0 px-4 flex items-center justify-center text-gray-400">
                {{ $attributes->get('prefix') }}
            </div>
        @endif
        
        <input 
            type="text"
            class="form-input w-full pl-14 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
            x-bind:value="value ? value.toLocaleString('en-US') : null"
            x-on:input="updateValue($event.target.value)"
        >
    </div>
</x-form.field>
