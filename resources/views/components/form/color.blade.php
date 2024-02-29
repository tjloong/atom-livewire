<x-form.field {{ $attributes }}>
    <div
        x-data="{
            show: false,
            value: @entangle($attributes->wire('model')),
        }"
        x-modelable="value"
        x-on:click="show = true"
        x-on:click.away="show = false"
        class="relative"
        {{ $attributes }}>
        <div x-ref="anchor" class="form-input w-full">
            <div
                x-bind:class="!value && 'form-input-caret'" 
                class="flex items-center gap-3">
                <div class="shrink-0">
                    <x-icon name="fill" class="text-gray-400"/>
                </div>

                <div x-show="!empty(value)" class="shrink-0 flex items-center justify-center">
                    <div
                        x-bind:style="{ backgroundColor: value }"
                        class="w-5 h-5 rounded-full shadow border"></div>
                </div>

                <input type="text"
                    x-model="value"
                    class="transparent grow"
                    placeholder="{{ tr($attributes->get('placeholder', 'app.label.select-color')) }}"
                    readonly>

                <div x-show="!empty(value)" x-on:click="value = null" class="shrink-0">
                    <x-close/>
                </div>
            </div>
        </div>

        <div x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-40 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden mt-px">
            <div class="grow grid grid-cols-11 gap-1 p-2 max-h-[300px] overflow-auto">
                @foreach (colors()->all() as $color)
                    <div
                        x-on:click="value = @js($color)"
                        x-bind:style="{ backgroundColor: @js($color) }"
                        class="cursor-pointer w-6 h-6 border rounded-full hover:ring-1 hover:ring-offset-1 hover:ring-gray-500"></div>                        
                @endforeach
            </div>
        </div>
    </div>
</x-form.field>
