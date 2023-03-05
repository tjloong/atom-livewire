@props([
    'placeholder' => __($attributes->get('placeholder')),
])

<x-form.field {{ $attributes }}>
    @if ($attributes->get('multiple') || $attributes->has('options'))
        <div
            x-data="{
                show: false,
                text: null,
                focus: false,
                wire: @js($attributes->wire('model')->value()),
                value: @js($attributes->get('value')),
                entangle: @entangle($attributes->wire('model')),
                multiple: @js($attributes->get('multiple', false)),
                options: @js($attributes->get('options', [])),
                get filteredOptions () {
                    const search = this.multiple ? this.text : this.value

                    if (!search) return this.options

                    return this.options.filter(opt => {
                        if (typeof opt === 'string') return opt.includes(search)
                        else if (typeof opt === 'object') return opt.name.includes(search) || opt.email.includes(search)
                    })
                },
                init () {
                    if (this.wire) {
                        this.value = this.entangle
                        this.$watch('entangle', (val) => this.value = val)
                    }
                },
                select (opt) {
                    const val = typeof opt === 'string' ? opt : opt.email

                    if (this.multiple) {
                        if (!this.value) this.value = []
                        if (!this.value.includes(val)) this.value.push(val)
                    }
                    else this.value = val

                    if (this.wire) this.entangle = this.value
                    else this.$dispatch('input', this.value)

                    this.close()
                },
                remove (val) {
                    const index = this.value.indexOf(val)
                    this.value.splice(index, 1)
                },
                close () {
                    this.show = false
                    this.text = null
                },
                onKeyup (e) {
                    if (!this.multiple) return

                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

                    if (e.code === 'Enter') {
                        if (this.filteredOptions.length) this.select(this.filteredOptions[0])
                        else if (regex.test(this.text)) this.select(this.text)
                    }
                    else if (e.code === 'Space' && regex.test(this.text)) this.select(this.text)
                },
            }"
            x-on:click.away="close()"
            class="relative"
        >
            <div x-ref="anchor" x-on:click="show = true">
                @if ($attributes->get('multiple'))
                    <div
                        x-bind:class="focus && 'active'"
                        {{ $attributes->class([
                            'form-input w-full flex flex-wrap items-center gap-2',
                            'error' => component_error(optional($errors), $attributes),
                        ])->except(['multiple', 'options', 'placeholder']) }}
                    >
                        <template x-for="(val, i) in (value || [])" x-bind:key="`${val}-${i}`">
                            <div class="shrink-0 bg-gray-200 rounded-lg overflow-hidden flex items-center gap-2 border border-gray-300">
                                <div class="pl-2 text-black text-sm" x-text="val"></div>
                                <a x-on:click="remove(val)" class="p-1 flex">
                                    <x-icon name="close" class="m-auto text-gray-500" size="14"/>
                                </a>
                            </div>
                        </template>

                        <input type="email"
                            x-model="text"
                            x-on:focus="focus = true"
                            x-on:blur="focus = false"
                            x-on:keyup.stop="onKeyup"
                            placeholder="{{ $placeholder }}"
                            class="grow"
                        >
                    </div>
                @else
                    <input type="email" 
                        x-model="value" 
                        placeholder="{{ $placeholder }}" 
                        {{ $attributes->class([
                            'form-input w-full',
                            'error' => component_error(optional($errors), $attributes),
                        ])->except(['multiple', 'options']) }}
                    >
                @endif
            </div>

            <div
                x-ref="dd"
                x-show="(show && filteredOptions.length) || text"
                x-transition.opacity
                class="absolute z-20 w-full mt-px bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[250px] overflow-hidden"
            >
                <div class="flex flex-col divide-y">
                    <template x-for="(opt, i) in filteredOptions" x-bind:key="`${opt}-${i}`">
                        <a x-on:click="select(opt)" class="py-2 px-4 text-gray-800 hover:bg-slate-100">
                            <template x-if="typeof opt === 'string'">
                                <div class="font-medium" x-text="opt"></div>
                            </template>

                            <template x-if="typeof opt === 'object'">
                                <div x-text="opt.name"></div>
                                <div x-text="opt.email" class="text-gray-500 font-medium"></div>
                            </template>
                        </a>
                    </template>

                    <template x-if="multiple && !filteredOptions.length && text">
                        <div class="p-1">
                            <div class="flex items-center justify-center gap-2 text-sm font-medium py-2 px-4 bg-gray-100">
                                <x-icon name="circle-info" class="text-gray-400" size="12"/>
                                <div class="text-gray-500">{{ __('Press spacebar to add email') }}</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @else
        <input type="email" placeholder="{{ $placeholder }}" {{ $attributes->class([
            'form-input w-full',
            'error' => component_error(optional($errors), $attributes),
        ])->except('placeholder') }}>
    @endif
</x-form.field>
