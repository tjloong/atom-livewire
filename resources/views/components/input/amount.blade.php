<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div 
        x-data="amountInput(
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
            class="form-input w-full pl-14"
            x-bind:value="value ? value.toLocaleString('en-US') : null"
            x-on:input="updateValue($event.target.value)"
        >
    </div>
</x-input.field>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('amountInput', (value) => ({
            value,

            stringToNumber (val) {
                if (Number.isFinite(val)) return val

                val = val.replace(/[^\d\.]+/g, '')
                val = val.replace(/(\..*)\./g, '$1')
                val = parseFloat(val)
                val = !val || !Number.isFinite(val) ? null : val

                return val || 0
            },

            updateValue (val) {
                this.value = this.stringToNumber(val)
                this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('input', { bubbles: true })))
            },
        }))
    })
</script>