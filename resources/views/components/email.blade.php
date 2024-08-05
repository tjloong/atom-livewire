@php
$prefix = $attributes->get('prefix');
$suffix = $attributes->get('suffix');
$icon = $attributes->get('icon', 'envelope');
$iconsuffix = $attributes->get('icon-suffix');
$multiple = $attributes->get('multiple', false);
$placeholder = $attributes->get('placeholder', 'app.label.email-address');
$options = $attributes->get('options', []);
$except = ['prefix', 'suffix', 'icon', 'multiple', 'placeholder', 'options'];
@endphp

<x-input {{ $attributes->except('class') }}>
    <div
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            search: null,
            pointer: null,
            options: @js($options),
            multiple: @js($multiple),

            get filtered () {
                return this.options
                .map(opt => (typeof opt === 'string' ? { name: opt, email: opt } : opt))
                .filter(opt => !empty(opt.email))
                .filter(opt => {
                    let text = this.multiple ? this.search : this.value
                    let search = text ? (opt.email.includes(text) || opt.name.includes(text)) : true
                    let exists = this.multiple ? (this.value || []).some(val => (val.email === opt.email)) : this.value === opt.email
                    return !exists && search
                })
            },

            init () {
                if (this.multiple && !this.value) this.value = []
            },

            open () {
                this.show = true
                this.$nextTick(() => {
                    this.$refs.input.focus()
                    this.$refs.dropdown.style.minWidth = this.$refs.anchor.offsetWidth+'px'
                })
            },

            close () {
                this.show = false
                this.pointer = null
            },

            select (index) {
                let opt

                if (index > 0) opt = this.filtered[+index]
                else if (this.pointer >= 0) opt = this.filtered[+this.pointer]
                else opt = this.filtered[0]

                if (this.multiple) {
                    if (opt) this.value.push(opt)
                    else if (this.search) {
                        this.search.split(';').map(str => str.trim()).forEach(str => this.value.push({
                            name: str,
                            email: str,
                        }))
                    }

                    this.search = null
                }
                else {
                    if (!opt) return
                    this.value = opt.email
                }
            },

            clear () {
                if (this.search) return
                if (!this.value.length) return

                this.value.pop()
            },

            validate (val) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)
            },

            navigate (e) {
                const isUp = e.key === 'ArrowUp'
                const isDown = e.key === 'ArrowDown'
                const max = this.filtered.length ? this.filtered.length - 1 : 0

                if (this.pointer === null) this.pointer = 0
                else if (isDown) this.pointer++
                else if (isUp) this.pointer--

                if (this.pointer < 0) this.pointer = 0
                if (this.pointer > max) this.pointer = max
            },
        }"
        x-modelable="value"
        x-on:keydown.down.stop="navigate"
        x-on:keydown.up.stop="navigate"
        x-on:keydown.esc.stop="close()"
        x-on:click.away="close()"
        class="px-3 inline-flex items-center w-full h-full">
        <div x-ref="anchor" x-on:click="open()" class="grow flex gap-3 py-2">
            @if ($prefix) <div class="shrink-0 text-gray-400">{!! $prefix !!}</div> @endif
            @if ($icon) <div class="shrink-0 text-gray-400"><x-icon :name="$icon"/></div> @endif

            <template X-if="multiple">
                <div class="grow flex items-center gap-2 flex-wrap">
                    <template x-for="(item, i) in value">
                        <div
                            x-bind:class="validate(item.email) ? 'bg-gray-100 border-gray-300' : 'bg-red-100 border-red-300 text-red-500'"
                            class="text-sm rounded-md border flex items-center">
                            <div class="pl-2 grow font-medium" x-text="item.email"></div>
                            <div class="shrink-0 px-2 flex cursor-pointer" x-on:click.stop="value.splice(i, 1)">
                                <x-icon name="xmark" class="m-auto"/>
                            </div>
                        </div>
                    </template>

                    <input type="email"
                        x-ref="input" 
                        x-model="search" 
                        x-on:focus="open()"
                        x-on:blur="search ? select() : close()"
                        x-on:keydown.enter.prevent="select()"
                        x-on:keydown.backspace="clear()"
                        x-on:keydown.;.prevent="select()"
                        x-on:keydown.comma.prevent="select()"
                        x-on:keydown.slash.prevent="select()"
                        x-on:input.prevent
                        class="appearance-none grow"
                        placeholder="{!! tr($placeholder) !!}">
                </div>
            </template>

            <template x-if="!multiple">
                <input type="email"
                    x-ref="input" 
                    x-model="value"
                    x-on:focus="open()"
                    x-on:blur="close()"
                    x-on:keydown.enter.prevent="select()"
                    x-on:input.prevent
                    class="appearance-none grow" 
                    placeholder="{{ tr($placeholder) }}">
            </template>

            @if ($iconsuffix) <div class="shrink-0 text-gray-400"><x-icon :name="$iconsuffix"/></div> @endif
            @if ($suffix) <div class="shrink-0 text-gray-400">{!! $suffix !!}</div> @endif
        </div>

        <div 
            x-ref="dropdown"
            x-anchor.offset.4="$refs.anchor"
            x-show="show && filtered?.length > 0"
            x-transition.opacity.duration.300
            class="bg-white z-1 border border-gray-300 rounded-md shadow-lg overflow-hidden">
            <div class="flex flex-col">
                <div class="text-sm text-gray-500 font-medium border-b py-2 px-4 bg-slate-100">
                    {{ tr('app.label.press-enter-to-select') }}
                </div>

                <template x-for="(opt, i) in filtered" x-bind:key="opt.email">
                    <div
                        x-on:click="select(i)"
                        x-on:mouseover="pointer = null"
                        x-bind:class="pointer === i ? 'bg-gray-50' : 'hover:bg-gray-50'"
                        class="py-2 px-4 cursor-pointer hover:bg-slate-50 border-b last:border-0">
                        <template x-if="opt.name === opt.email || !opt.name">
                            <div class="font-medium" x-text="opt.email"></div>
                        </template>

                        <template x-if="opt.name && opt.email">
                            <div>
                                <div class="font-medium" x-text="opt.name"></div>
                                <div class="text-sm text-gray-500" x-text="opt.email"></div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-input>