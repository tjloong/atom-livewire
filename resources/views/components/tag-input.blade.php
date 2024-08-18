@php
$options = $attributes->get('options', []);
$placeholder = $attributes->get('placeholder', 'app.label.add-tags');
@endphp

<x-input class="flex flex-col" {{ $attributes->except('class') }}>
    <div
        wire:ignore
        x-data="{
            value: @entangle($attributes->wire('model')),
            options: @js($options),
            show: false,
            text: null,

            get filteredOptions () {
                return this.options.filter(val => (
                    !this.value.includes(val) && (empty(this.text) || val.includes(this.text))
                ))
            },

            init () {
                if (!this.value) this.value = []
            },

            open () {
                this.show = true
                this.$nextTick(() => this.focus())
            },

            close () {
                this.show = false
                this.text = null
            },

            select (val = null) {
                if (!val && !empty(this.text)) {
                    const opt = this.filteredOptions[0]
                    if (opt) this.value.push(opt)
                    else {
                        this.value.push(this.text)
                        this.options.push(this.text)
                    }
                }
                else if (!this.value.includes(val)) {
                    this.value.push(val)
                }

                this.text = null
                this.focus()
            },

            remove (val = null) {
                if (!val) {
                    this.value = []
                }
                else {
                    const index = this.value.indexOf(val)
                    this.value.splice(index, 1)
                }

                this.focus()
            },

            backspace (e) {
                if (!this.text || !this.text.length) {
                    this.value.pop()
                    this.focus()
                }
            },

            focus () {
                this.$refs.text.focus()
            },
        }"
        x-modelable="value"
        x-on:click="open"
        class="group relative grow flex flex-col w-full">
        <button
            type="button" 
            x-ref="anchor"
            x-bind:class="show && 'active'"
            class="grow w-full focus:outline-none">
            <div class="grow group/button inline-flex gap-3 py-1.5 px-3 w-full text-left">
                @if ($icon = $attributes->get('icon'))
                    <div class="shrink-0">
                        <x-icon :name="$icon" class="text-gray-400"/>
                    </div>
                @endif
    
                <div class="grow flex flex-wrap gap-2">
                    <template x-for="(val, i) in value.filter(Boolean)">
                        <div class="bg-slate-200 rounded border border-gray-200 py-[1.5px]">
                            <div class="flex items-center max-w-[200px]">
                                <div x-text="val" class="px-2 truncate text-xs font-medium"></div>
                                <div class="shrink-0 text-xs flex items-center justify-center px-1">
                                    <x-close x-on:click.stop="remove(val)"/>
                                </div>
                            </div>
                        </div>
                    </template>    
    
                    <input type="text"
                        x-ref="text"
                        x-model="text"
                        x-on:keydown.enter.prevent="select()"
                        x-on:keydown.backspace="backspace"
                        class="grow"
                        placeholder="{{ tr($placeholder) }}">
                </div>
                
                <div
                    x-show="value && value.length"
                    x-on:click.stop="remove()"
                    class="shrink-0 cursor-pointer text-gray-400 hover:text-gray-600">
                    <x-icon name="xmark"/>
                </div>
            </div>
        </button>

        <div
            x-ref="dropdown"
            x-show="show"
            x-anchor.offset.4="$refs.anchor"
            x-on:click.away="close"
            class="bg-white shadow-lg w-full rounded-md border border-gray-300 overflow-hidden z-10">
            <div
                x-show="!filteredOptions.length" 
                class="py-2 px-4 text-sm text-gray-500 font-medium text-center">
                {{ tr('app.label.enter-add-tag') }}
            </div>

            <div x-show="filteredOptions.length" class="flex flex-col divide-y">
                <template x-for="opt in filteredOptions" hidden>
                    <div
                        x-on:click.stop="select(opt)"
                        class="py-2 px-4 cursor-pointer flex items-center gap-3 hover:bg-slate-50">
                        <x-icon name="tag" class="text-gray-400"/>
                        <span x-text="opt"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-input>