<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div
        x-data="phoneInput(
            @if ($wire = $attributes->wire('model')->value()) $wire.get('{{ $wire }}'),
            @elseif ($value = $attributes->get('value')) @js($value),
            @endif
        )"
        x-on:click.away="close()"
        wire:ignore
        class="relative"
    >
        <div {{ $attributes }} x-init="$watch('value', value => $dispatch('input', value))"></div>

        <div x-ref="input" class="relative">
            <a
                x-on:click.prevent="open()"
                class="absolute top-0 bottom-0 px-3 flex items-center justify-center text-sm text-gray-500"
            >
                <div class="flex items-center gap-2">
                    <img x-bind:src="flag" style="width: 18px;">
                    <span x-text="code"></span>
                </div>
            </a>

            <input
                x-model="number"
                x-on:input="input"
                type="text" 
                class="form-input w-full pl-24 pr-4"
            >
        </div>

        <div
            x-ref="dropdown"
            class="absolute left-0 right-0 bg-white border drop-shadow rounded-md h-56 overflow-auto text-sm hidden"
        >
            @foreach (countries() as $country)
                <a 
                    x-on:click.prevent="code = '{{ $country['dialCode'] }}'; input()"
                    class="flex items-center gap-2 py-2 px-4 border-b hover:bg-gray-100"
                    data-country-code="{{ $country['dialCode'] }}"
                    data-country-flag="{{ $country['flag'] }}"
                >
                    <img src="{{ $country['flag'] }}" style="width: 18px;">
                    <div class="text-gray-500 w-16 flex-shrink-0">{{ $country['dialCode'] }}</div>
                    <div class="font-medium text-gray-800">{{ $country['name'] }}</div>
                </a>                
            @endforeach
        </div>        
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('phoneInput', (value, code = '+60') => ({
                value,
                code,
                flag: null,
                number: null,

                get countries () {
                    return Array.from(this.$refs.dropdown.querySelectorAll('[data-country-code]'))
                        .map(el => ({ 
                            code: el.getAttribute('data-country-code'), 
                            flag: el.getAttribute('data-country-flag'),
                        }))
                },

                get flag () {
                    if (!this.code) return 
                    return this.countries.find(country => (country.code === this.code)).flag
                },
    
                init () {
                    if (this.value?.startsWith('+')) {
                        const code = this.countries.find(country => (this.value.startsWith(country.code)))

                        if (code) {
                            this.code = code
                            this.number = this.value.replace(code, '')
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
    
                    floatPositioning(this.$refs.input, this.$refs.dropdown, {
                        placement: 'bottom',
                        flip: true,
                    })
                },
                close () {
                    this.$refs.dropdown.classList.add('hidden')
                },
            }))
        })
    </script>
</x-input.field>

