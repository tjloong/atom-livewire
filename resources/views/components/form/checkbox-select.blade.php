<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div 
        x-data="{
            value: @js($attributes->get('value')),
            wire: @js(!empty($attributes->wire('model')->value())),
            entangle: @entangle($attributes->wire('model')),
            init () {
                if (this.wire) {
                    this.value = this.entangle
                    this.$watch('value', val => this.entangle = val)
                }
            },
        }"
        class="{{ $attributes->get('class', 'grid gap-2 md:grid-cols-3') }}"
    >
        @foreach ($attributes->get('options') as $opt)
            <div
                id="checkbox-select-{{ data_get($opt, 'value') }}"
                x-data="{
                    get selected() {
                        return this.value === @js(__(data_get($opt, 'value')))
                    },
                }"
                x-on:click="value = @js(data_get($opt, 'value'))"
                x-bind:class="!selected && 'opacity-60 cursor-pointer'"
                class="bg-white border shadow rounded-lg p-4 flex flex-col gap-1"
            >
                <div class="flex items-center gap-2">
                    <x-icon x-show="!selected" name="circle-check" size="16" class="shrink-0 text-gray-400"/>
                    <x-icon x-show="selected" name="circle-check" size="16" class="shrink-0 text-green-500"/>
                    <div class="font-semibold">
                        {{ __(data_get($opt, 'label')) }}
                    </div>
                </div>

                @if ($desc = data_get($opt, 'description'))
                    <div class="text-sm text-gray-500 font-medium">
                        {{ __($desc) }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-form.field>
