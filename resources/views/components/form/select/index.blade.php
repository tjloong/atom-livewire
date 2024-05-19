@php
    $icon = $attributes->get('icon');
    $multiple = $attributes->get('multiple', false);
    $callback = $attributes->get('callback');
    $params = $attributes->get('params');
    $searchable = $attributes->get('searchable', true);
    $clearable = $attributes->get('clearable', true);
    $disabled = $attributes->get('disabled', false);
    $placeholder = tr($attributes->get('placeholder', 'app.label.select-option'));

    $options = collect($attributes->get('options'))->map(fn($opt) => is_string($opt) ? [
        'value' => $opt, 'label' => $opt,
    ] : $opt)->toArray();

    $except = ['options', 'icon', 'class', 'multiple', 'callback', 'params', 'disabled', 'searchable', 'placeholder', 'wire:model', 'wire:model.defer'];
@endphp

<x-form.field {{ $attributes->except('class') }}>
    <div
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            options: @js($options),
            multiple: @js($multiple),
            searchable: @js($searchable),
            clearable: @js($clearable),
            disabled: @js($disabled),
            callback: @js($callback),
            params: @js($params),
            endpoint: @js(route('__select')),
            show: false,
            search: null,
            pointer: null,
            loading: false,

            get selection () {
                let found = this.options.filter(opt => (opt.selected))
                if (this.multiple) return found
                else return found.length ? found[0] : null
            },

            get isSearchable () {
                return this.searchable && (this.options.length || !empty(this.search))
            },

            get noSelection () {
                return this.multiple
                    ? (!this.value || !this.value.length)
                    : (this.value === null || this.value === undefined)
            },

            get noOptions () {
                return !this.options.filter(opt => (!opt.hidden)).length
            },

            init () {
                this.$nextTick(() => {
                    if (this.callback) {
                        if (!this.noSelection) this.fetch()
                        this.$watch('search', () => this.fetch())
                        this.$watch('value', () => !this.noSelection && this.fetch())
                    }
                    else {
                        this.filter()
                        this.$watch('search', () => this.filter())
                    }
                })
            },

            open () {
                if (this.disabled) return

                this.show = true
                this.adjustDropdown()

                if (this.callback && !this.options.length) this.fetch()
                else this.filter()
            },

            close () {
                this.show = false
                this.search = null
                this.loading = false
                this.pointer = null
            },

            adjustDropdown () {
                if (!this.show) return

                this.$nextTick(() => {
                    if (this.$refs.search) this.$refs.search.focus()
                    if (this.$refs.dropdown) this.$refs.dropdown.style.minWidth = this.$refs.anchor.offsetWidth+'px'
                })
            },

            filter () {
                this.options.forEach(opt => {
                    let searchable = opt.searchable || `${opt.label} ${opt.small} ${opt.caption}`.trim().toLowerCase()
                    let found = this.callback || !this.search || searchable.includes(this.search.toLowerCase())
                    let selected = this.isSelected(opt)
                    let hidden = (this.multiple && this.isSelected(opt)) || !found

                    Object.assign(opt, { selected, hidden })
                })

                this.pointer = null
                this.adjustDropdown()
            },

            fetch () {
                this.loading = true

                let payload = { 
                    callback: this.callback, 
                    params: { ...this.params, search: this.search },
                    value: this.value,
                }

                return ajax(this.endpoint).post(payload)
                .then(res => this.options = [...res])
                .then(() => this.filter())
                .then(() => this.loading = false)
            },

            select (value) {
                if (this.multiple) {
                    const index = this.value.indexOf(value)
                    if (index === -1) this.value.push(value)
                    else this.value.splice(index, 1)
                }
                else {
                    this.value = value
                }

                this.filter()

                if (!this.multiple) this.close()
            },

            remove (value = null) {
                if (value === null) {
                    if (this.multiple) this.value = []
                    else this.value = null
                }
                else {
                    let index = this.value.indexOf(value)
                    this.value.splice(index, 1)
                }

                this.filter()
            },

            autoselect () {
                let li = this.$refs.options.querySelector('li.focus')

                if (li) li.click()
                else {
                    let opt = this.options.find(opt => (!opt.hidden))
                    if (opt) this.select(opt.value)
                }
            },

            navigate (dir) {
                if (!this.show) this.open()
                else {
                    let min = this.options.findIndex(opt => (!opt.hidden))
                    let max = this.options.findLastIndex(opt => (!opt.hidden))

                    if (this.pointer === null) this.pointer = min
                    else if (dir === 'down' && this.pointer < max) this.pointer++
                    else if (dir === 'up' && this.pointer > min) this.pointer--

                    if (this.options[this.pointer]?.hidden && this.pointer > min && this.pointer < max) {
                        this.navigate(dir)
                    }
                    else {
                        let el = Array.from(this.$refs.options.querySelectorAll('li'))[this.pointer]
                        el.scrollIntoView({ block: 'end' })
                    }
                }
            },

            isSelected (opt) {
                return (this.multiple && this.value && this.value.includes(opt.value))
                    || (!this.multiple && this.value === opt.value)
            },
        }"
        x-modelable="value"
        x-on:click.away="close()"
        x-on:keydown.down.stop="navigate('down')"
        x-on:keydown.up.stop="navigate('up')"
        x-on:keydown.esc.prevent="close()"
        x-on:keydown.enter.stop="autoselect()"
        {{ $attributes->except($except)}}>
        <div x-ref="anchor" x-on:click="open()">
            @if (isset($anchor))
                {{ $anchor }}
            @else
                <button type="button" x-bind:disabled="disabled" class="relative flex gap-3 {{ $attributes->get('class', 'form-input w-full select') }}">
                    @if ($icon)
                        <div class="shrink-0 text-gray-400"><x-icon :name="$icon"/></div>
                    @endif

                    <div x-bind:class="multiple && !noSelection && !loading && 'first:-ml-1.5'" class="grow">
                        <template x-if="noSelection">
                            <input type="text" class="transparent w-full cursor-pointer" placeholder="{!! $placeholder !!}" readonly>
                        </template>

                        <template x-if="!noSelection && !show && loading">
                            <input type="text" class="transparent w-full cursor-pointer" placeholder="{!! tr('app.label.loading') !!}" readonly>
                        </template>

                        <template x-if="!noSelection">
                        @if ($slot->isNotEmpty())
                            {{ $slot }}
                        @else
                            <div class="w-full">
                                <template x-if="multiple">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <template x-for="item in selection">
                                            <div class="bg-slate-200 rounded border border-gray-200">
                                                <div class="flex items-center max-w-[200px]">
                                                    <div x-text="item.label" class="px-2 truncate text-xs font-medium"></div>
                                                    <div x-show="show" class="shrink-0 text-sm flex items-center justify-center px-1">
                                                        <x-close x-on:click.stop="remove(item)"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!multiple">
                                    <div x-text="selection?.label" class="text-left"></div>
                                </template>
                            </div>
                        @endif
                        </template>
                    </div>
                    
                    <template x-if="!loading && clearable && show && !noSelection">
                        <div class="absolute right-1 top-1 bottom-1 bg-white p-1 flex" x-on:click.stop="remove()">
                            <div class="w-5 h-5 rounded-md flex m-auto text-gray-400 hover:text-gray-600 hover:bg-gray-200">
                                <x-icon name="xmark" class="m-auto"/>
                            </div>
                        </div>
                    </template>
                </button>
            @endif
        </div>

        <div
            x-ref="dropdown"
            x-show="show"
            x-transition.opacity.duration.300
            x-anchor.offset.4="$refs.anchor"
            class="bg-white shadow-lg rounded-md border border-gray-300 overflow-hidden z-10">
            <template x-if="!options?.length && loading">
                <div class="flex flex-col divide-y animate-pulse">
                    <div class="p-4"><div class="h-2 w-1/2 bg-gray-300 rounded-md"></div></div>
                    <div class="p-4"><div class="h-2 w-1/2 bg-gray-300 rounded-md"></div></div>
                </div>
            </template>

            <template x-if="options?.length && loading">
                <x-box.loading/>
            </template>

            <template x-if="isSearchable">
                <div x-on:input.stop class="rounded-t-md border bg-slate-100 py-2 px-4 flex items-center gap-3">
                    <div class="shrink-0 text-gray-400"><x-icon name="search"/></div>

                    <input type="text"
                        x-ref="search"
                        x-model.debounce.500ms="search"
                        placeholder="{{ tr('app.label.search') }}"
                        class="grow transparent w-full">

                    <div
                        x-show="search && !loading" 
                        x-on:click="() => {
                            search = null
                            $nextTick(() => $refs.search.focus())
                        }"
                        class="shrink-0 text-gray-400 cursor-pointer">
                        <x-icon name="arrow-left"/>
                    </div>
                </div>
            </template>

            <div class="flex flex-col divide-y">
                <ul x-ref="options" class="max-h-[250px] overflow-auto">
                    <template x-for="(opt, i) in options" x-bind:key="`${random()}_${opt.value}`">
                        <li x-bind:class="{
                            'hidden': opt.hidden,
                            'bg-gray-50 focus': pointer === i,
                            'hover:bg-gray-50': pointer !== i,
                        }" x-on:mouseover="pointer = null" x-on:click="select(opt.value)" class="cursor-pointer border-b last:border-0">
                            @if (isset($option) && $option->isNotEmpty())
                                {{ $option }}
                            @else
                                <div class="px-4 py-2 flex items-center gap-3 hover:bg-slate-50">
                                    <template x-if="opt.color">
                                        <div class="shrink-0 w-4 h-4 rounded-full shadow" x-bind:style="{ backgroundColor: opt.color }"></div>
                                    </template>

                                    <div class="grow">
                                        <div x-text="opt.label"></div>
                                        <div x-text="opt.small || opt.caption" class="text-sm text-gray-500 truncate"></div>
                                    </div>
                                </div>
                            @endif
                        </li>
                    </template>

                    <template x-if="!loading && noOptions">
                        <div class="px-5">
                            <x-no-result xs/>
                        </div>
                    </template>
                </ul>

                @if (isset($foot) && $foot->isNotEmpty())
                    <div x-show="!loading" class="p-3 bg-gray-100">{{ $foot }}</div>
                @endif 
            </div>
        </div>
    </div>
</x-form.field>
