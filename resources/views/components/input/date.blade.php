<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div
        x-data="dateInput(@entangle($attributes->wire('model')->value()), {
            minDate: '{{ $attributes->has('min') ? $attributes->get('min') : '' }}',
            maxDate: '{{ $attributes->has('max') ? $attributes->get('max') : '' }}',
        })"
        x-on:click.away="close()"
        class="relative"
        {{ $attributes->except(['error', 'required', 'caption']) }}
    >
        <div x-ref="input" class="relative">
            <div class="absolute top-0 bottom-0 text-gray-400 flex items-center justify-center px-2.5">
                <x-icon name="calendar" size="20px"/>
            </div>
    
            <input
                x-ref="input"
                x-bind:value="formatDate(value)"
                x-on:focus="open()"
                type="text"
                class="w-full form-input px-10 cursor-pointer"
                placeholder="Pick a date"
                readonly
            >

            <div x-show="loading" class="absolute top-0 bottom-0 right-0 flex items-center justify-center px-1">
                <x-loader size="18px"/>
            </div>
    
            <a
                class="absolute top-0 bottom-0 right-0 text-gray-500 flex items-center justify-center px-2.5"
                x-on:click="clear()"
                x-show="!loading && value !== null"
            >
                <x-icon name="x" size="20px"/>
            </a>
        </div>

        <div
            x-ref="dropdown"
            x-show="show"
            class="absolute z-10"
        >
            <div x-ref="datepicker"></div>
        </div>
    </div>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dateInput', (value, config) => ({
                value,
                fp: null,
                show: false,
                loading: false,
    
                open () {
                    if (!window.flatpickr) this.loading = true
    
                    ScriptLoader.load([
                        { src: 'https://cdn.jsdelivr.net/npm/flatpickr', type: 'js' },
                        { src: 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', type: 'css' },
                    ]).then(() => {
                        this.loading = false
                        this.show = true
    
                        this.fp = flatpickr(this.$refs.datepicker, {
                            inline: true,
                            dateFormat: 'Y-m-d',
                            defaultDate: this.value,
                            onClose: () => this.close(),
                            onChange: (selectedDate, dateStr) => this.value = dateStr,
                            ...config,
                        })
                    })
                },
                close () { 
                    this.show = false
                },
                clear () {
                    this.value = null
                    this.$dispatch('input', null)
                },
            }))
        })
    </script>
</x-input.field>

