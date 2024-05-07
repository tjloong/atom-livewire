@php
    $placeholder = $attributes->get('placeholder', 'app.label.select-color');
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            show: false,
            value: @entangle($attributes->wire('model')),
        }"
        x-modelable="value"
        x-on:click="show = true"
        x-on:click.away="show = false"
        {{ $attributes }}>
        <button type="button"
            x-ref="anchor"
            class="form-input w-full flex items-center gap-3">
            <div class="shrink-0"><x-icon name="fill" class="text-gray-400"/></div>

            <div class="grow flex items-center gap-3">
                <div
                    x-show="value"
                    x-bind:style="{ backgroundColor: value }"
                    class="w-5 h-5 rounded-full shadow border"></div>

                <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                    x-bind:value="value || null"
                    class="transparent grow cursor-pointer">
            </div>

            <div class="shrink-0">
                <x-icon name="dropdown-caret" x-show="!value"/>

                <div x-show="value" x-on:click.stop="value = null" class="cursor-pointer text-gray-400 hover:text-gray-600">
                    <x-icon name="xmark"/>
                </div>
            </div>

        </button>

        <div
            x-ref="dropdown"
            x-show="show"
            x-anchor.offset.4="$refs.anchor"
            x-transition.opacity.duration.300
            class="z-10 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden">
            <div class="grow grid grid-cols-11 gap-1 p-2 max-h-[300px] overflow-auto">
                @foreach (color()->all() as $color)
                    <div
                        x-on:click="value = @js($color)"
                        x-bind:style="{ backgroundColor: @js($color) }"
                        class="cursor-pointer w-6 h-6 border rounded-full hover:ring-1 hover:ring-offset-1 hover:ring-gray-500"></div>                        
                @endforeach
            </div>
        </div>
    </div>
</x-form.field>
