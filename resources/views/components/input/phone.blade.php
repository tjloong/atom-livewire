<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div
        x-data="phoneInput($wire.get('{{ $attributes->wire('model')->value() }}'), @js($countries))"
        x-on:click.away="close()"
        wire:ignore
        class="relative"
    >
        <div {{ $attributes }} x-init="$watch('value', value => $dispatch('input', value))"></div>

        <div x-ref="input" class="relative">
            <a
                x-text="code"
                x-on:click.prevent="open()"
                class="absolute top-0 bottom-0 px-3 flex items-center justify-center text-sm text-gray-500"
            ></a>

            <input
                x-model="number"
                x-on:input="input"
                type="text" 
                class="form-input w-full px-14"
            >
        </div>

        <div
            x-ref="dropdown"
            class="absolute left-0 right-0 bg-white border drop-shadow rounded-md h-56 overflow-auto text-sm hidden"
        >
            <template x-for="country in countries" x-bind:key="country.name">
                <a 
                    x-on:click.prevent="code = country.code; input()"
                    class="flex items-center space-x-2 py-2 px-4 border-b hover:bg-gray-100"
                >
                    <div x-text="country.code" class="text-gray-500 w-16 flex-shrink-0"></div>
                    <div x-text="country.name" class="font-medium text-gray-800"></div>
                </a>
            </template>
        </div>
    </div>
</x-input.field>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('phoneInput', (value, countries, code = '+60') => ({
            value,
            countries,
            code,
            number: null,

            init () {
                if (this.value?.startsWith('+')) {
                    const country = this.countries.find(val => (this.value.startsWith(val.code)))

                    if (country) {
                        this.code = country.code
                        this.number = this.value.replace(country.code, '')
                    }
                }
                else this.number = this.value
            },
            input () {
                this.value = this.number ? `${this.code}${this.number}` : null
                this.close()
            },
            open () {
                this.$refs.dropdown.classList.remove('hidden')
                this.$refs.dropdown.classList.add('opacity-0')

                floatPositioning(this.$refs.input, this.$refs.dropdown, {
                    placement: 'bottom',
                    flip: true,
                }).then(() => this.$refs.dropdown.classList.remove('opacity-0'))
            },
            close () {
                this.$refs.dropdown.classList.add('hidden')
            },
        }))
    })
</script>
