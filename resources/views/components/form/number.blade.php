@props([
    'class' => [
        'transparent' => 'w-full border-0 p-0 pr-10 focus:ring-0',
        'normal' => 'form-input w-full',
    ],
])

<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    @if ($units = $attributes->get('unit') ?? $attributes->get('units') ?? null)
        @if (is_string($units))
            <div class="relative">
                <input type="number" class="{{ $class['normal'] }}" {{ $attributes->except(['unit', 'units']) }}>
                <div class="absolute top-0 bottom-0 right-8 flex items-center justify-center">
                    <div class="font-medium text-gray-600">{{ $units }}</div>
                </div>
            </div>

        @elseif (is_array($units))
            <div
                x-data="numberInputWithUnit(
                    @if ($attributes->wire('model')->value()) $wire.get('{{ $attributes->wire('model')->value() }}'),
                    @elseif ($attributes->has('value')) @js($attributes->get('value')),
                    @else null,
                    @endif
                    @js($units)
                )" 
                class="relative"
            >
                <div class="hidden" {{ $attributes->whereStartsWith('wire:model') }}>
                    <input x-ref="numberInputWithUnit" x-bind:value="updated" type="text" x-on:input="dd('hello')">
                </div>

                <input
                    x-model="n"
                    type="number" 
                    class="{{ $class['normal'] }}"
                    {{ $attributes->filter(fn($val, $key) => in_array($key, ['required', 'disabled', 'maxlength', 'step', 'min', 'max'])) }}
                >
                
                <div class="absolute top-0 bottom-0 right-8 flex items-center justify-center">
                    <select 
                        x-model="unit"
                        class="bg-gray-100 rounded-md text-sm text-gray-600 font-medium uppercase py-0.5 px-2 border-0 min-w-[100px]"
                        @if ($attributes->get('disabled')) disabled @endif
                    >
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}">{{ str($unit)->headline() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('numberInputWithUnit', (value, units) => ({
                        n: null,
                        unit: units[0],
    
                        get updated () {
                            if (this.n && this.unit) return [this.n, this.unit].join(' ')
                            else return null
                        },
    
                        init () {
                            if (value) {
                                const [n, unit] = value.split(' ')
                                this.n = Number(n)
                                this.unit = unit
                            }
    
                            this.$watch('updated', (val) => {
                                this.$refs.numberInputWithUnit.dispatchEvent(
                                    new Event('input', { bubbles: true })
                                )
                            })
                        },
                    }))
                })
            </script>
        @endif
    @else
        @if ($attributes->has('transparent'))
            <div class="relative" x-data>
                <input x-ref="numberInput" type="number" {{ $attributes->class([$class['transparent']]) }}>
                <a
                    class="absolute top-0 right-0 bottom-0 flex justify-center items-center text-gray-400"
                    x-on:click.prevent="$refs.numberInput.select()"
                >
                    <x-icon name="pencil" size="18px"/>
                </a>
            </div>
        @else
            <input type="number" {{ $attributes->class([$class['normal']]) }}>
        @endif
    @endif
</x-form.field>