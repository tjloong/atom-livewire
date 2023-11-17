@php
    $icon = $attributes->get('icon');
    $multiple = $attributes->get('multiple', false);
    $callback = $attributes->get('callback');
    $params = $attributes->get('params');
    $search = $attributes->get('search', 'lazy');
    $options = $attributes->get('options', []);
    $placeholder = tr($attributes->get('placeholder', 'common.label.select-option'));
@endphp

<x-form.field {{ $attributes }}>
    <div 
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            search: @js($search),
            multiple: @js($multiple),
            options: @js($options),
            show: false,
            focus: false,
            loading: false,
            get selected () {
                return this.multiple
                    ? this.options.filter(opt => (this.value.includes(opt.value)))
                    : this.options.find(opt => (opt.value === this.value))
            },
            get filtered () {
                return this.options.filter(opt => (!opt.hidden))
            },
            get isEmpty () {
                return this.multiple
                    ? (!this.value || !this.value.length)
                    : (this.value === null || this.value === undefined)
            },
            fetch (callback, params) {
                if (!callback) return

                this.loading = true
                axios.post(@js(route('__select.get')), { callback, params, value: this.value })
                    .then(res => this.options = res.data)
                    .finally(() => this.loading = false)
            },
            filter (text, callback, params) {
                if (this.search === 'lazy') {
                    this.options = this.options.map(opt => ({
                        ...opt,
                        hidden: !empty(text) && (
                            !opt.label.toLowerCase().includes(text.toLowerCase())
                            && !opt.small?.toLowerCase().includes(text.toLowerCase())
                        ),
                    }))
                }
                else this.fetch(callback, { ...params, search: text })
            },
            open (callback, params) {
                if (this.show) this.close()
                else {
                    if (this.multiple && !this.value) this.value = []
                    this.fetch(callback, params)
                    this.show = true
                    this.$nextTick(() => {
                        this.$refs.search?.focus()
                        floatDropdown(this.$refs.anchor, this.$refs.dd)
                    })
                }
            },
            close () {
                this.$refs.search.value = ''
                this.show = false
                this.focus = false
            },
            select (opt) {
                if (this.multiple) {
                    const index = this.value.indexOf(opt.value)
                    if (index === -1) this.value.push(opt.value)
                    else this.value.splice(index, 1)
                }
                else if (this.value === opt.value) this.value = null
                else this.value = opt.value

                this.$dispatch('input', this.value)
                this.close()
            },
            remove (opt = null) {
                if (opt === null) {
                    if (this.multiple) {
                        this.value = []
                        this.$dispatch('input', [])
                    }
                    else this.value = null
                }
                else {
                    const index = this.value.indexOf(opt.value)
                    this.value.splice(index, 1)
                    this.$dispatch('input', this.value)
                }
            },
            isSelected (opt) {
                return this.multiple && this.value && this.value.includes(opt.value)
                    || !this.multiple && this.value === opt.value
            },
        }"
        x-init="() => {
            if (!isEmpty) fetch(@js($callback), @js($params))
            $watch('value', () => value && !selected && fetch(@js($callback), @js($params)))
        }"
        x-modelable="value"
        x-on:click.away="close()"
        class="relative"
        {{ $attributes->except(['options', 'icon', 'multiple', 'callback', 'params', 'search', 'placeholder'])}}>
        <button type="button" 
            x-ref="anchor"
            x-on:click.prevent="open(@js($callback), @js($params))"
            x-bind:class="show && 'active'"
            class="form-input w-full">
            <template x-if="isEmpty">
                <div class="flex items-center gap-3">
                    @if ($icon) <div class="shrink-0"><x-icon :name="$icon" class="text-gray-400"/></div> @endif
                    <input type="text" class="grow tranparent cursor-pointer" placeholder="{{ $placeholder }}" readonly>
                    <div class="shrink-0">
                        <x-icon name="dropdown-caret"/>
                    </div>
                </div>
            </template>
    
            <template x-if="!isEmpty">
                <div class="flex gap-2 cursor-pointer">
                    <div class="grow">
                        @if ($slot->isNotEmpty()) {{ $slot }}
                        @else
                            <template x-if="multiple">
                                <div class="flex items-center gap-2 flex-wrap pr-4">
                                    <template x-for="item in selected" x-bind:key="item.value">
                                        <div class="bg-slate-200 rounded-md px-2 border border-gray-200">
                                            <div class="flex items-center gap-2 max-w-[200px]">
                                                <div x-text="item.label" class="truncate text-xs font-medium"></div>
                                                <div class="shrink-0 text-sm flex items-center justify-center">
                                                    <x-close x-on:click.stop="remove(item)"/>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!multiple">
                                <div class="grid">
                                    <div class="text-left truncate" x-text="selected?.label"></div>
                                </div>
                            </template>
                        @endif
                    </div>

                    <div class="shrink-0">
                        <x-close x-on:click.stop="remove()"/>
                    </div>
                </div>
            </template>
        </button>

        <div x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-40 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden w-full mt-px min-w-[300px]">
            <div x-show="search" x-on:click="$refs.search.focus()" class="p-3 border-b">
                <div x-bind:class="focus && 'active'" class="form-input flex items-center gap-3 w-full">
                    <div class="shrink-0 text-gray-400">
                        <x-icon name="search"/>
                    </div>

                    <input type="text"
                        x-ref="search"
                        x-on:focus="focus = true"
                        x-on:blur="focus = false"
                        x-on:input.debounce.500ms.stop="filter($event.target.value, @js($callback), @js($params))"
                        x-on:keydown.enter.prevent="filtered.length && select(filtered[0])"
                        class="transparent grow" 
                        placeholder="{{ tr('common.label.search') }}">

                    <div
                        x-show="!loading" 
                        x-on:click="$refs.search.value = '' && filter(null, @js($callback), @js($params))"
                        class="shrink-0 text-sm text-gray-500 cursor-pointer">
                        <x-icon name="arrow-left"/>
                    </div>

                    <div x-show="loading" class="shrink-0 flex items-center justify-center text-theme">
                        <x-spinner size="20"/>
                    </div>
                </div>
            </div>

            <div class="flex flex-col divide-y">
                <ul class="max-h-[250px] overflow-auto flex flex-col divide-y">
                    <template x-for="opt in filtered">
                        <li x-on:click="select(opt)" class="cursor-pointer">
                            <div x-show="opt.is_group" class="py-2 px-4 flex items-center gap-3 font-semibold bg-gray-100">
                                <template x-if="opt.icon">
                                    <i x-bind:class="opt.icon.split(' ').map(val => `fa-${val}`)"></i>
                                </template>
                                <div class="grow font-semibold" x-text="opt.label"></div>
                                <x-icon name="chevron-down" class="shrink-0"/>
                            </div>

                            <div 
                                x-show="!opt.is_group"
                                x-bind:class="isSelected(opt) 
                                    ? 'border-l-4 border-green-500 bg-slate-100 pl-3 pr-4'
                                    : 'px-4 hover:bg-slate-50'"
                                class="py-2 flex items-center gap-3 cursor-pointer">
                                <template x-if="opt.avatar?.url || typeof opt.avatar === 'string'">
                                    <div class="shrink-0 w-10 h-10 rounded-full border shadow">
                                        <img x-bind:src="opt.avatar?.url || opt.avatar" class="w-full h-full object-cover">
                                    </div>
                                </template>

                                <template x-if="typeof opt.avatar === 'object' && !opt.avatar?.url && opt.avatar?.placeholder">
                                    <div class="shrink-0 w-10 h-10 rounded-full bg-gray-500 text-gray-100 shadow flex items-center justify-center">
                                        <div class="font-bold" x-text="opt.avatar?.placeholder.substring(0, 2).toUpperCase()"></div>
                                    </div>
                                </template>

                                <template x-if="opt.hasOwnProperty('flag')">
                                    <div class="shrink-0 w-5 h-5 flex">
                                        <img x-show="opt.flag" x-bind:src="opt.flag" class="w-full object-contain m-auto">
                                        <div x-show="!opt.flag" class="w-full h-full border rounded bg-gray-100"></div>
                                    </div>
                                </template>

                                <div class="grow">
                                    <div class="flex items-center gap-3">
                                        <div class="grow grid">
                                            <div x-text="opt.label" class="truncate"></div>
                                        </div>
                                        <div x-text="opt.remark" class="shrink-0 text-right text-sm text-gray-500 font-medium"></div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="grow grid">
                                            <div class="text-sm text-gray-500 truncate" x-text="opt.small"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </template>

                    <template x-if="!loading && !filtered.length">
                        <x-no-result xs
                            title="common.empty.option.title"
                            subtitle="common.empty.option.subtitle"/>
                    </template>
                </ul>

                @isset($foot)
                    @if ($foot->isNotEmpty())
                        {{ $foot }}
                    @else
                        <a class="py-3 px-4 flex items-center justify-center gap-2" {{ $foot->attributes->except('label', 'icon') }}>
                            @if ($icon = $foot->attributes->get('icon')) <x-icon :name="$icon"/> @endif
                            {{ tr($foot->attributes->get('label', '')) }}
                        </a>
                    @endif
                @endisset
            </div>
        </div>
    </div>
</x-form.field>