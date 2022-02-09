<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div 
        x-data="currencyInput(
            @if ($wire = $attributes->wire('model')->value()) $wire.get('{{ $wire }}')
            @elseif ($value = $attributes->get('value')) @js($value)
            @endif
        )"
        class="relative"
    >
        <div {{ $attributes->except('error', 'required', 'caption') }} x-init="$watch('value', val => $dispatch('input', val))"></div>

        <div class="absolute top-0 bottom-0 left-0 px-4 flex items-center justify-center text-gray-400">
            {{ $attributes->get('currency') }}
        </div>
        <input 
            type="text"
            class="form-input w-full pl-14"
            x-bind:value="value ? value.toLocaleString('en-US') : null"
            x-on:input="value = stringToNumber($event.target.value)"
        >
    </div>
</x-input.field>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('currencyInput', (value) => ({
            value,

            stringToNumber (val) {
                if (Number.isFinite(val)) return val

                val = val.replace(/[^\d\.]+/g, '')
                val = val.replace(/(\..*)\./g, '$1')
                val = parseFloat(val)
                val = !val || !Number.isFinite(val) ? null : val

                return val
            },
        }))
    })
</script>