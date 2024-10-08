@php
$icon = $attributes->get('icon');
$multiple = $attributes->get('multiple', false);
$searchable = $attributes->get('searchable', true);
$clearable = $attributes->get('clearable', true);
$disabled = $attributes->get('disabled', false);
$transparent = $attributes->get('transparent', false);
$placeholder = $attributes->get('placeholder') ?? 'app.label.select-option';
$callback = $attributes->get('callback');
$filters = $attributes->get('filters') ?? $attributes->get('filter');
$wireoptions = $attributes->wire('options');
$options = $wireoptions->value ? [] : $attributes->get('options', []);

if ($placeholder === true) $placeholder = $label;

if (is_string($options)) {
    if ($static = app('select')->getStatic($attributes->get('options'))) $options = $static;
    else {
        $options = [];
        $callback = $attributes->get('options');
    }
}

$options = collect($options)->map(function($opt) {
    if (is_string($opt)) return ['value' => $opt, 'label' => $opt];
    elseif (is_enum($opt)) return ['value' => $opt->value, 'label' => $opt->label(), 'color' => method_exists($opt, 'color') ? $opt->color() : null];
    else return $opt;
})->values()->all();

$except = ['options', 'icon', 'class', 'multiple', 'callback', 'filter', 'filters', 'disabled', 'searchable', 'placeholder', 'wire:options', 'wire:model', 'wire:model.defer'];
@endphp

<x-input class="flex flex-col" {{ $attributes->except('class') }}>
    <div
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            options: @if($wireoptions->value) @entangle($wireoptions) @else @js($options) @endif,
            multiple: @js($multiple),
            searchable: @js($searchable),
            disabled: @js($disabled),
            endpoint: @js(route('__select')),
            show: false,
            search: null,
            pointer: null,
            loading: false,
            callback: {
                name: @js($callback),
                filters: @js($filters),
            },

            get selection () {
                let found = this.options.filter(opt => (opt.selected))
                if (this.multiple) return found
                else return found.length ? found[0] : null
            },

            get isClearable () {
                return @js($clearable) && !this.loading && this.show && !this.noSelection
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
                    if (this.callback.name) {
                        if (!this.noSelection) this.fetch()
                        this.$watch('search', () => this.fetch())
                        this.$watch('value', (value, old) => {
                            let isEqual = this.multiple
                                ? (value.length === old.length && value.every(val => (old.indexOf(val) !== -1)))
                                : value === old
                            if (!isEqual && !this.noSelection) setTimeout(() => this.fetch(), 300)
                        })
                    }
                    else {
                        this.filter()
                        this.$watch('search', () => this.filter())
                    }
                })

                this.$el.getSelectedValue = () => (this.value)
            },

            open () {
                if (this.disabled) return
                if (this.show) return

                this.show = true
                this.adjustDropdown()

                if (this.callback.name) this.fetch()
                else this.filter()
            },

            close () {
                if (!this.show) return

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
                    let found = this.callback.name || !this.search || searchable.includes(this.search.toLowerCase())
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
                    name: this.callback.name,
                    filters: { search: this.search },
                    selected: this.value,
                }

                if (typeof this.callback.filters === 'string') {
                    let dependant = this.$el.closest('.group\\/field').parentNode.querySelector(`[data-field-name=${this.callback.filters}]`)
                    if (dependant) payload.filters[this.callback.filters] = dependant.getSelectedValue()
                }
                else {
                    payload.filters = {
                        ...payload.filters,
                        ...this.callback.filters,
                    }
                }

                return Atom.ajax(this.endpoint).post(payload)
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
                let li = this.$refs.options.querySelector('li.focus > [data-option]')

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
        x-on:keydown.down.stop.prevent="navigate('down')"
        x-on:keydown.up.stop.prevent="navigate('up')"
        x-on:keydown.esc.stop.prevent="close()"
        x-on:keydown.enter.stop.prevent="autoselect()"
        class="grow flex flex-col"
        {{ $attributes->except($except)}}>
        <button
            type="button"
            x-ref="anchor"
            x-on:click="show ? close() : open()"
            x-bind:disabled="disabled"
            class="grow w-full focus:outline-none">
            <div class="grow group/button inline-flex gap-3 w-full text-left py-1.5 {{ $transparent ? '' : 'px-3' }}">
                @if ($icon)
                    <div class="shrink-0 text-gray-400"><x-icon :name="$icon"/></div>
                @endif

                <template x-if="noSelection" hidden>
                    <div class="grow cursor-pointer text-gray-400">
                        {!! tr($placeholder) !!}
                    </div>
                </template>

                <template x-if="!noSelection" hidden>
                    @if ($slot->isNotEmpty())
                        {{ $slot }}
                    @else
                        <div class="grow">
                            <template x-if="multiple" hidden>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <template x-for="item in selection">
                                        <div class="bg-slate-200 rounded border border-gray-200 py-[1.5px]">
                                            <div class="flex items-center max-w-[200px]">
                                                <div x-text="item.label" class="px-2 truncate text-xs font-medium"></div>
                                                <div
                                                    x-show="show"
                                                    x-on:click.stop="remove(item.value)"
                                                    class="shrink-0 text-xs flex items-center justify-center px-1 cursor-pointer">
                                                    <x-icon xmark size="12"/>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!multiple" hidden>
                                <div class="grid">
                                    <div x-text="selection?.label" class="truncate"></div>
                                </div>
                            </template>
                        </div>
                    @endif
                </template>

                <div class="shrink-0">
                    <div x-show="isClearable" x-on:click.stop="remove()" class="cursor-pointer text-gray-400 hover:text-gray-600">
                        <x-icon name="xmark"/>
                    </div>
                </div>

                <div class="shrink-0 w-3 h-6 flex items-center justify-center">
                    <x-icon dropdown/>
                </div>
            </div>
        </button>

        <div
            x-ref="dropdown"
            x-show="show"
            x-transition.opacity.duration.300
            x-anchor.offset.4="$refs.anchor"
            x-on:click.away="close()"
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
                <div x-on:input.stop class="rounded-t-md border-b bg-slate-100 py-2 px-4 flex items-center gap-3">
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
                <ul x-ref="options" class="max-h-[250px] overflow-auto flex flex-col divide-y">
                    <template x-for="(opt, i) in options" x-bind:key="`${random()}_${opt.value}`" hidden>
                        <li
                            x-bind:class="{
                                'hidden': opt.hidden,
                                'bg-slate-50 focus': pointer === i,
                                'hover:bg-slate-50': pointer !== i,
                            }"
                            x-on:mouseover="pointer = null"
                            class="last:rounded-b-md">
                            @if (isset($option) && $option->isNotEmpty())
                                {{ $option }}
                            @else
                                <template x-if="opt.is_group">
                                    <div x-text="opt.label" class="py-2 px-4 text-sm uppercase text-gray-500 bg-gray-50 font-medium pointer-events-none"></div>
                                </template>

                                <template x-if="opt.badge">
                                    <div
                                        data-option
                                        x-badge="opt.badge"
                                        x-on:click.stop="select(opt.value)"
                                        class="py-2 px-4 cursor-pointer">
                                    </div>
                                </template>

                                <template x-if="!opt.is_group && !opt.badge">
                                    <div
                                        data-option
                                        x-on:click.stop="select(opt.value)"
                                        class="py-2 px-4 flex gap-3 cursor-pointer">
                                        <template x-if="opt.color">
                                            <div class="shrink-0 w-4 h-4 rounded shadow my-1" x-bind:style="{ backgroundColor: opt.color }"></div>
                                        </template>

                                        <div class="grow">
                                            <div x-text="opt.label"></div>
                                            <div x-text="opt.small || opt.caption" class="text-sm text-gray-500 truncate"></div>
                                        </div>
                                    </div>
                                </template>
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
</x-input>
