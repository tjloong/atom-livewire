@php
$placeholder = $attributes->get('placeholder', 'app.label.select-color');
@endphp

<x-input class="h-auto" {{ $attributes->except('class') }}>
    <div
        x-data="{
            show: false,
            value: @entangle($attributes->wire('model')),

            open () {
                if (this.show) return
                this.show = true
            },

            close () {
                this.show = false
            },
        }"
        x-modelable="value"
        class="py-1.5"
        {{ $attributes->except('placeholder') }}>
        <button
            type="button"
            x-ref="anchor"
            x-on:click="open()"
            x-on:click.away="close()"
            x-on:keydown.esc="close()"
            class="group/button inline-flex items-center gap-3 px-3 w-full h-full text-left">
            <div class="shrink-0 text-gray-400">
                <x-icon name="fill"/>
            </div>

            <div class="grow flex items-center gap-3">
                <div
                    x-show="value"
                    x-bind:style="{ backgroundColor: value }"
                    class="w-5 h-5 rounded shadow border"></div>

                <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                    x-bind:value="value || null"
                    class="transparent grow cursor-pointer">
            </div>

            <div class="shrink-0">
                <div x-show="value" x-on:click="value = null" class="cursor-pointer text-gray-400 hover:text-gray-600 hidden group-hover/button:block group-focus/button:block">
                    <x-icon name="xmark"/>
                </div>
            </div>

            <div class="shrink-0 w-3 h-full select-caret"></div>
        </button>

        <div
            x-ref="dropdown"
            x-show="show"
            x-anchor.bottom-start.offset.4="$refs.anchor"
            x-transition.opacity.duration.300
            class="bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden">
            <div class="grow grid grid-cols-11 gap-1 p-2 max-h-[300px] overflow-auto">
                @foreach (color()->all() as $color)
                    <div
                        x-on:click="value = @js($color)"
                        x-bind:style="{ backgroundColor: @js($color) }"
                        class="cursor-pointer w-6 h-6 border rounded hover:ring-1 hover:ring-offset-1 hover:ring-gray-500">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-input>
