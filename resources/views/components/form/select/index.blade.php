@props([
    'model' => $attributes->wire('model')->value(),
    'value' => data_get($this, $attributes->wire('model')->value()),
    'icon' => $attributes->get('icon'),
    'multiple' => $attributes->get('multiple'),
    'isAutocomplete' => $attributes->get('autocomplete', false),
    'placeholder' => $attributes->get('placeholder')
        ? __($attributes->get('placeholder'))
        : (($val = component_label($attributes)) ? __('Select').' '.__($val) : __('Please Select')),
])

<x-form.field {{ $attributes }}>
    <div
        x-cloak 
        x-data="{
            text: null,
            focus: false,
            callback: @js($attributes->has('wire:search') && !$isAutocomplete),
            open () {
                this.focus = true
                this.$nextTick(() => {
                    this.$refs.search?.focus()
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                })
            },
            search (str) {
                if (this.callback) this.$dispatch('search', str)
                else {
                    const elems = this.$refs.dd?.querySelectorAll('[data-searchable]')
                    if (!elems) return
    
                    Array.from(elems).forEach((elem) => {
                        const searchable = elem.getAttribute('data-searchable')
                        if (!empty(searchable) && searchable.includes(str)) {
                            elem.parentNode.classList.remove('hidden')
                        }
                        else elem.parentNode.classList.add('hidden')
                    })
                }
            },
        }"
        x-on:click.away="focus = false" 
        class="relative"
        id="{{ component_id($attributes, 'select-input') }}"
        {{ $attributes->wire('search') }}
    >
        <div x-ref="anchor"
            x-on:click="open"
            x-bind:class="{ 
                'active': focus, 
                {{-- 'select': @js(empty($value))  --}}
            }"
            {{ $attributes->class([
                'form-input w-full flex items-center gap-2',
                'error' => component_error(optional($errors), $attributes),
            ])->only('class') }}
        >
            <div class="flex items-center gap-2 w-full {{ empty($value) ? 'form-input-caret' : '' }}">
                @if ($icon) <x-icon :name="$icon" class="text-gray-400"/> @endif
    
                @if ($isAutocomplete)
                    <div class="grow" {{ $attributes->wire('model') }}>
                        <input type="text"
                            value="{{ $value }}"
                            x-on:input.debounce.400ms="search($event.target.value)"
                            class="form-input transparent w-full" 
                            placeholder="{{ $placeholder }}" 
                        />
                    </div>
                @elseif ($value)
                    @isset($selected) {{ $selected }}
                    @else
                        <div class="grow">
                            @if ($multiple)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($value as $val)
                                        <div class="bg-slate-200 rounded-md px-2 text-sm font-medium border border-gray-200 flex items-center gap-2 max-w-[200px]">
                                            <div class="grid">
                                                <div class="truncate text-xs">
                                                    {{ data_get(collect($options)->firstWhere('value', $val), 'label') }}                    
                                                </div>
                                            </div>
                                            <div class="flex" wire:click.stop="$set(@js($model), @js(collect($value)->reject($val)->toArray()))">
                                                <x-close size="12"/>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{ data_get(collect($options)->firstWhere('value', $value), 'label') }}
                            @endif
                        </div>
                    @endif
                @else
                    <input type="text" class="form-input transparent grow" placeholder="{{ $placeholder }}" readonly>
                @endif
    
                @if (!empty($value)) <x-close wire:click.stop="$set('{{ $model }}', null)"/> @endif
            </div>

            @isset($button) 
                <div class="shrink-0">
                    @if ($button->isNotEmpty()) {{ $button }}
                    @else
                        @php $buttonlabel = $button->attributes->get('label') @endphp
                        @php $buttonicon = $button->attributes->get('icon') @endphp
                        <a {{ $button->attributes->class([
                            'flex items-center justify-center gap-1 rounded-full -mr-1 text-sm',
                            $buttonlabel ? 'px-2 py-0.5' : null,
                            !$buttonlabel && $buttonicon ? 'p-1' : null,
                            $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
                        ]) }}>
                            @if ($buttonicon) <x-icon :name="$buttonicon" size="12"/> @endif
                            {{ __($buttonlabel) }}
                        </a>
                    @endif
                </div>
            @endisset
        </div>

        <div x-ref="dd"
            x-show="focus"
            x-transition.opacity
            class="absolute z-20 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden w-full mt-px min-w-[300px] flex flex-col divide-y"
        >
            @if (!$isAutocomplete && (count($options) > 15 || $attributes->wire('search')->value()))
                <div class="p-3">
                    <input type="text" 
                        x-ref="search" 
                        x-on:input.debounce.300ms="search($event.target.value)"
                        class="form-input w-full" 
                        placeholder="{{ __('Search') }}"
                    >
                </div>
            @endif

            <div class="max-h-[250px] overflow-auto flex flex-col divide-y">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @forelse ($options as $opt)
                        @if (data_get($opt, 'is_group'))
                            <div class="py-2 px-4 flex items-center gap-3 font-semibold bg-gray-100">
                                @if ($icon = data_get($opt, 'icon')) <x-icon :name="$icon" class="shrink-0 text-gray-500"/> @endif
                                <div class="grow font-semibold">{{ data_get($opt, 'label') }}</div>
                                <x-icon name="chevron-down" class="shrink-0" size="12"/>
                            </div>
                        @else
                            <div 
                                @if ($multiple) 
                                    wire:click="$set(@js($model), @js(collect($value)->push(data_get($opt, 'value'))->unique()->toArray()))"
                                @else
                                    wire:click="$set(@js($model), @js(data_get($opt, 'value')))"
                                @endif
                                x-on:click.debounce.100ms="focus = false"
                                class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-100"
                                id="{{ uniqid() }}"
                            >
                                @if (($avatar = data_get($opt, 'avatar')) || ($avatarPlaceholder = data_get($opt, 'avatar_placeholder')))
                                    <div class="shrink-0">
                                        <x-thumbnail :url="$avatar ?? null" :placeholder="$avatarPlaceholder" size="30" color="random" circle/>
                                    </div>
                                @elseif ($image = data_get($opt, 'image'))
                                    <div class="shrink-0">
                                        <x-thumbnail :url="$image" size="40"/>
                                    </div>
                                @elseif ($flag = data_get($opt, 'flag'))
                                    <div class="shrink-0 w-4 h-4">
                                        <img src="{{ $flag }}" class="w-full h-full object-contain object-center">
                                    </div>
                                @endif

                                <div class="grow grid">
                                    @if (($label = data_get($opt, 'label')) && ($small = data_get($opt, 'small'))) 
                                        <div class="font-medium truncate">{{ $label }}</div> 
                                        <div class="text-gray-500 text-sm truncate">{{ $small }}</div>
                                    @else
                                        <div class="truncate">{{ data_get($opt, 'label') }}</div>
                                    @endif
                                </div>

                                @if (($remark = data_get($opt, 'remark')) || ($status = data_get($opt, 'status')))
                                    <div class="shrink-0 text-right">
                                        @if (!empty($remark)) <div class="text-sm font-medium text-gray-500">{{ $remark }}</div> @endif
                                        @if (!empty($status))
                                            @if (is_array($status))
                                                @foreach ($status as $key => $val)
                                                    <x-badge :label="$val" :color="$key"/>
                                                @endforeach
                                            @else <x-badge :label="$status"/>
                                            @endif
                                        @endif
                                    </div>
                                @endif

                                <div class="hidden" data-searchable="{{ data_get($opt, 'searchable') }}"></div>
                            </div>
                        @endif
                    @empty
                        <x-empty-state title="No options available" subtitle="" size="sm"/>
                    @endforelse
                @endif
            </div>

            @isset($foot)
                @if ($foot->isNotEmpty())
                    {{ $foot }}
                @else
                    @php $icon = $foot->attributes->get('icon') @endphp
                    @php $label = $foot->attributes->get('label') @endphp
                    @php $href = $foot->attributes->get('href') @endphp
                    <x-link :icon="$icon" :label="$label" :href="$href" class="p-4 flex items-center justify-center"/>
                @endif
            @endisset
        </div>
    </div>
</x-form.field>

{{-- <div
    x-cloak
    x-data="{
        show: false,
        text: null,
        config: @js($config),
        wire: @js(!empty($model)),
        value: @js($attributes->get('value')),
        entangle: @entangle($model),
        options: [],
        loading: false,
        paginator: {},
        searchable: true,
        get searchable () {
            return this.config.callback || (this.config.options || []).length > 10
        },
        init () {
            if (this.wire) {
                this.value = this.entangle || null
                this.$watch('entangle', (val) => this.value = val)
            }

            this.$watch('text', (val) => this.$nextTick(() => this.float()))
            this.retrieve()
        },
        open () {
            if (this.show) this.close()
            else {
                this.show = true
                this.$nextTick(() => {
                    this.$refs.search.querySelector('input')?.focus()
                    this.float()
                    this.retrieve()
                })
            }
        },
        close () {
            this.show = false
            this.text = null
        },
        float () {
            floatDropdown(this.$refs.anchor, this.$refs.dd)
        },
        retrieve (page = 1) {
            if (!this.config.callback) return this.setOptions()

            this.loading = true
            this.$wire.call(this.config.callback, this.text, page, [this.value].filter(Boolean).flat())
                .then(res => this.paginator = res)
                .then(() => this.setOptions())
                .finally(() => this.loading = false)
        },
        setOptions () {
            this.options = []
            
            this.config.options.forEach(opt => {
                this.options.push(this.formatOption(opt))
                if (opt.hasOwnProperty('subs')) opt.subs.forEach(sub => this.options.push(this.formatOption(sub)))
            })

            const paginatorData = this.paginator?.data || []
            paginatorData.forEach(item => {
                const val = this.formatOption(item)
                const index = this.options.findIndex(opt => opt.value === val.value)

                if (index !== -1) this.options[index] = val
                else this.options.push(val)

                if (item.hasOwnProperty('subs')) {
                    item.subs.forEach(sub => {
                        const subval = this.formatOption(sub)
                        const subindex = this.options.findIndex(opt => opt.value === subval.value)

                        if (subindex !== -1) this.options[subindex] = subval
                        else this.options.push(subval)
                    })
                }
            })
        },
        getFilteredOptions () {
            if (!this.text) return this.options
            
            return this.options.filter(opt => (
                opt.label?.toLowerCase().includes(this.text.toLowerCase()) 
                    || opt.small?.toLowerCase().includes(this.text.toLowerCase())
            ))
        },
        select (val = null) {
            if (this.config.multiple) {
                if (!this.value) this.value = []
                if (val !== null) this.value.push(val)
                else this.value = []
            }
            else this.value = val

            this.input()
            this.$nextTick(() => this.close())
        },
        remove (sel) {
            this.value = this.value.filter(val => val !== sel)
            this.input()
        },
        input () {
            if (this.wire) this.entangle = this.value
            else this.$dispatch('input', this.value)
        },
        formatOption (val) {
            if (['string', 'number'].includes(typeof val)) return { value: val, label: val }

            let opt = {
                value: val.value || val.id || val.code,
                label: val.label || val.name || val.title || val.value || val.id || val.code,
                small: val.small || val.description || val.caption,
                isGroup: val.hasOwnProperty('subs') || val.hasOwnProperty('children'),
            }

            opt.image = val.image || null
            opt.avatar = val.avatar || null
            opt.flag = val.flag || null
            opt.remark = val.remark || null
            opt.status = val.status ? {
                text: val.status,
                color: val.status_color || 'text-gray-800 bg-gray-100',
            } : null

            if (opt.label && typeof opt.label === 'object') opt.label = opt.label[@js(app()->currentLocale())]
            if (opt.image && typeof opt.image === 'object') opt.image = opt.image.url
            if (opt.avatar && typeof opt.avatar === 'object') opt.avatar = opt.avatar.url

            return opt
        },
    }"
    x-on:click.away="close()"
    class="relative"
    {{ $attributes
        ->merge(['id' => component_id($attributes, 'select-input')])
        ->whereStartsWith(['x-', 'id']) }}
>
    {{-- <div
        x-ref="anchor" 
        x-on:click="open()" 
        x-bind:class="{ 'active': show, 'select': empty(value) }"
        {{ $attributes->class([
            'form-input w-full',
            'error' => component_error(optional($errors), $attributes),
        ])->only('class') }}
    >
        <template x-if="!empty(value)">
            <div
                x-bind:class="!config.multiple && 'items-center'"
                class="flex gap-2"
            >
                <div class="grow flex flex-wrap gap-2">
                    <template x-if="config.multiple">
                        <template 
                            x-for="(val, i) in options.filter(opt => (value.includes(opt.value)))"
                            x-bind:key="`${val.value}-${i}`"
                        >
                            <div class="bg-slate-200 rounded-md px-2 text-sm font-medium border border-gray-200 flex items-center gap-2 max-w-[200px]">
                                <div class="grid">
                                    <div x-text="val.label" class="truncate text-xs"></div>
                                </div>
                                <x-close x-on:click.stop="remove(val.value)" size="12"/>
                            </div>
                        </template>
                    </template>

                    <template x-if="!config.multiple">
                        <div class="grid">
                            <div x-text="options.find(opt => (opt.value === value))?.label" class="truncate"></div>
                        </div>
                    </template>
                </div>
                <div class="shrink-0 flex items-center justify-center">
                    <x-close x-on:click.stop="select()"/>
                </div>
            </div>
        </template>

        <template x-if="empty(value)">
            <div class="text-gray-400 grid">
                <div class="truncate" x-text="config.placeholder"></div>
            </div>
        </template>
    </div> --}}

    {{-- <div 
        x-ref="dd"
        x-show="show"
        x-transition.opacity
        class="absolute z-20 bg-white shadow-lg rounded-md border border-gray-300 overflow-hidden w-full min-w-[300px]"
    >
        <div class="relative">
            <div x-ref="search" x-show="searchable" class="p-3 border-b">
                <x-form.text
                    x-model="text"
                    x-on:input.debounce.300ms="retrieve()"
                    placeholder="Search"
                    prefix="icon:search"
                >
                    <x-slot:postfix>
                        <x-close x-show="text" x-on:click.stop="text = null"/>
                    </x-slot:postfix>
                </x-form.text>
            </div>

            <div 
                x-show="paginator?.last_page > 1" 
                x-show="paginator?.last_page > 1" 
                x-show="paginator?.last_page > 1" 
                class="relative px-4 py-2 flex items-center justify-evenly gap-4 text-sm border-b"
            >
                <div x-show="loading" class="absolute inset-0 bg-white/50"></div>

                <div class="shrink-0">
                    <a 
                        x-show="paginator?.current_page > 1"
                        x-on:click="retrieve(paginator?.current_page - 1)" 
                        x-on:click="retrieve(paginator?.current_page - 1)" 
                        x-on:click="retrieve(paginator?.current_page - 1)" 
                        class="flex items-center gap-2 text-gray-600 bg-gray-100 rounded-md py-1 px-2 shadow"
                    >
                        <x-icon name="chevron-left" size="12"/> {{ __('Previous') }}
                    </a>
                </div>

                <div x-text="`${paginator?.current_page}/${paginator?.last_page}`" class="grow text-center text-sm font-medium"></div>

                <div class="shrink-0">
                    <a 
                        x-show="paginator?.current_page < paginator?.last_page"
                        x-on:click="retrieve(paginator?.current_page + 1)" 
                        x-on:click="retrieve(paginator?.current_page + 1)" 
                        x-on:click="retrieve(paginator?.current_page + 1)" 
                        class="flex items-center gap-2 text-gray-600 bg-gray-100 rounded-md py-1 px-2 shadow"
                    >
                        {{ __('Next') }} <x-icon name="chevron-right" size="12"/>
                    </a>
                </div>
            </div>

            <div x-show="!getFilteredOptions().length">
                <x-empty-state title="No options available" subtitle="" size="sm"/>
            </div>

            <div x-show="getFilteredOptions().length" class="max-h-[250px] overflow-auto">
                <template x-for="(opt, index) in getFilteredOptions()" x-bind:key="`${opt.value}-${index}`">
                    <div
                        x-on:click="!opt.isGroup && select(opt.value)"
                        x-bind:class="{
                            'bg-gray-100': opt.isGroup,
                            'cursor-pointer hover:bg-slate-100': !opt.isGroup,
                        }" 
                        class="py-2 px-4 flex items-center gap-3 border-b last:border-b-0"
                    >
                        <div 
                            x-show="opt.avatar || opt.image || opt.flag"
                            x-bind:class="{
                                'w-8 h-8 rounded-full': opt.avatar,
                                'w-8 h-8 rounded-md shadow': opt.image,
                                'w-4 h-4': opt.flag,
                            }"
                            class="shrink-0 bg-gray-100 overflow-hidden"
                        >
                            <img
                                x-bind:src="opt.avatar || opt.image || opt.flag" 
                                x-bind:class="{
                                    'object-cover': opt.avatar || opt.image,
                                    'object-contain object-center': opt.flag,
                                }"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="grow">
                            <div class="flex gap-3 flex-wrap">
                                <div 
                                    x-text="opt.label"
                                    x-bind:class="opt.isGroup && 'font-bold'"
                                    class="grow"
                                ></div>

                                <div 
                                    x-show="opt.remark" 
                                    x-text="opt.remark"
                                    class="shrink-0 text-sm font-medium text-gray-500"
                                ></div>
                            </div>

                            <div class="flex gap-3 flex-wrap">
                                <div class="grow grid">
                                    <div 
                                        x-show="opt.small" 
                                        x-text="opt.small" 
                                        class="truncate font-medium text-sm text-gray-400"
                                    ></div>
                                </div>

                                <div
                                    x-show="opt.status"
                                    x-text="opt.status?.text" 
                                    x-bind:class="['text-sm px-2 font-medium rounded-full shadow'].concat(opt.status?.color)"
                                    class="shrink-0"
                                ></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="loading" class="absolute inset-0 bg-white/80 flex items-center justify-center text-theme">
                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="width: 45px; height: 45px">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <div class="border-t grid divide-y">
            @isset($footlink)
                <a 
                    href="{{ $footlink->attributes->get('href') }}"
                    class="p-4 text-center flex items-center justify-center gap-2 hover:bg-slate-100"
                >
                    @php $icon = $footlink->attributes->get('icon') @endphp
                    @php $label = $footlink->attributes->get('label') @endphp

                    <x-icon :name="$icon ?? $label"/>

                    @if ($label) {{ __($label) }}
                    @else {{ $footlink }}
                    @endif
                </a>
            @endisset

            @if ($slot->isNotEmpty())
                <div class="py-2 px-4">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</div> --}}
