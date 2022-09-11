<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div 
        x-data="{
            page: 1,
            show: false,
            text: null,
            list: [],
            loading: false,
            searchable: false,
            paginator: {},
            value: @entangle($attributes->wire('model')),
            callback: @js($attributes->get('callback')),
            options: @js($options),
            selected: @js($selected),
            multiple: @js($multiple),
            get isEmpty () {
                return !this.placeholder || (Array.isArray(this.placeholder) && !this.placeholder.length)
            },
            getOptions () {
                let options = []
                let data = this.options || []
                if (this.paginator.data) data = this.paginator.data

                data.forEach(val => {
                    options.push(this.formatOption(val))
                    if (val.children) val.children.forEach(child => options.push(this.formatOption(child)))
                })

                return options
            },
            formatOption (val) {
                if (typeof val === 'string') return { value: val, label: val }

                let opt = {
                    value: val.value || val.id || val.code,
                    label: val.label || val.name || val.title,
                    small: val.small || val.description || val.caption,
                }

                if (val.hasOwnProperty('children')) {
                    opt.isGroup = true
                }
                else {
                    opt.isGroup = false
                    opt.image = val.image
                    opt.avatar = val.avatar
                    opt.flag = val.flag
                    opt.remark = val.remark
                    opt.status = {
                        text: val.status,
                        color: val.status_color ? val.status_color.values().join(' ') : null,
                    }
                }

                if (typeof opt.label === 'object') opt.label = opt.label[@js(app()->currentLocale())]
                if (typeof opt.image === 'object') opt.image = opt.image.url
                if (typeof opt.avatar === 'object') opt.avatar = opt.avatar.url

                return opt
            },
            getPlaceholder () {
                if (this.selected) return this.selected
                else {
                    const options = this.getOptions()

                    if (this.multiple) {
                        const filtered = options.filter(opt => (this.value.includes(opt.value)))
                        return filtered.length ? filtered : null
                    }
                    else return options.find(opt => (opt.value === this.value)) || null
                }
            },
            search () {
                this.list = this.getOptions()

                if (this.text) {
                    const text = this.text.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, `\\$&`)
                    const regex = text ? new RegExp(text, 'i') : null
                    if (regex) this.list = this.list.filter(opt => (regex.test(opt.label) || regex.test(opt.small)))
                }
            },
            open () {
                if (this.show) this.close()
                else {
                    this.show = true
                    this.$nextTick(() => {
                        floatDropdown(this.$refs.anchor, this.$refs.dd)
                        this.$refs.search && this.$refs.search.focus()
                        this.retrieve()
                        this.searchable = this.list.length > 10 || this.callback
                    })
                }
            },
            close () {
                this.show = false
                this.text = null
            },
            select (opt) {
                if (this.multiple) {
                    if (!this.value) this.value = []
                    this.value.push(opt.value)
                }
                else this.value = opt.value

                this.$nextTick(() => this.close())
            },
            remove (sel) {
                this.value = this.value.filter(val => val !== sel.value)
            },
            clearValue () {
                if (this.multiple) this.value = []
                else this.value = null
                this.$nextTick(() => this.close())
            },
            clearText () {
                this.text = null
                this.$nextTick(() => this.retrieve())
            },
            next () {
                this.page++
                this.retrieve()
            },
            prev () {
                this.page--
                this.retrieve()
            },
            retrieve () {
                if (!this.callback) this.search()
                else {
                    this.loading = true
                    this.$wire.call(this.callback, this.text, this.page)
                        .then(res => this.paginator = res)
                        .then(() => this.search())
                        .finally(() => this.loading = false)
                }
            },
        }"
        x-on:click.away="close()"
        class="relative"
        @if (!$attributes->get('callback')) id="{{ $uid }}" @endif
    >
        <div
            x-ref="anchor" 
            x-on:click="open()" 
            x-bind:class="{
                'active': show,
                'select': getPlaceholder() === null,
            }"
            class="form-input w-full {{ $attributes->get('error') ? 'error' : '' }}"
        >
            <template x-if="getPlaceholder() === null">
                <div class="text-gray-400 grid">
                    <div class="truncate">
                        {{ __($attributes->get('placeholder', 'Select '.($label ?? 'an option'))) }}
                    </div>
                </div>
            </template>

            <template x-if="getPlaceholder() !== null && multiple">
                <div class="flex gap-2">
                    <div class="grow flex flex-wrap gap-2">
                        <template x-for="sel in getPlaceholder()">
                            <div class="bg-slate-200 rounded-md py-1 px-2 text-sm font-medium border border-gray-200 flex items-center gap-2 max-w-[200px]">
                                <div class="grid">
                                    <div x-text="sel.label" class="truncate text-xs"></div>
                                </div>
                                <a x-on:click="remove(sel)" class="flex text-gray-500">
                                    <x-icon name="xmark" size="12px"/>
                                </a>
                            </div>
                        </template>
                    </div>

                    <a x-on:click="clearValue()" class="flex shrink-0 text-gray-500">
                        <x-icon name="xmark" size="16px" class="m-auto"/>
                    </a>
                </div>
            </template>

            <template x-if="getPlaceholder() !== null && !multiple">
                <div class="flex items-center justify-between gap-2">
                    <div class="grid">
                        <div x-text="getPlaceholder()?.label" class="truncate"></div>
                    </div>
                    <a x-on:click="clearValue()" x-on:click.stop class="flex shrink-0 text-gray-500">
                        <x-icon name="xmark" size="16px" class="m-auto"/>
                    </a>
                </div>
            </template>
        </div>

        <div 
            x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-20 bg-white shadow-lg rounded-md border border-gray-300 overflow-hidden w-full min-w-[300px]"
        >
            <div x-show="searchable" class="p-3 border-b">
                <div class="py-2 px-4 flex items-center gap-2 form-input">
                    <x-icon name="search" size="15px" class="text-gray-400"/>
                    <div class="grow">
                        <input type="text"
                            x-ref="search"
                            x-model="text"
                            x-on:input.debounce.400ms="retrieve()"
                            placeholder="{{ __('Search') }}"
                            class="form-input transparent w-full"
                        >
                    </div>
                    <a x-show="text" x-on:click="clearText()" class="flex">
                        <x-icon name="xmark" size="15px" class="text-gray-500 m-auto"/>
                    </a>
                </div>
            </div>

            <div 
                x-bind:class="getOptions().length > 10 ? 'h-[250px]' : 'max-h-[250px]'"
                class="overflow-auto"
            >
                <div x-show="loading" class="p-6 flex items-center justify-center h-full w-full text-theme">
                    <svg class="animate-spin"
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24"
                        style="width: 45px; height: 45px"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <div x-show="!loading && !list.length" class="p-6 flex items-center justify-center gap-3">
                    <x-icon name="folder-open" size="20px" class="text-gray-400"/>
                    <div class="text-gray-500 font-medium">{{ __('The list is empty') }}</div>
                </div>

                <div 
                    x-show="paginator.last_page > 1" 
                    class="relative px-4 py-2 flex items-center justify-evenly gap-4 text-sm border-b"
                >
                    <div x-show="loading" class="absolute inset-0 bg-white/50"></div>

                    <div class="shrink-0">
                        <a 
                            x-show="paginator.current_page > 1"
                            x-on:click="prev()" 
                            class="flex items-center gap-2 text-gray-600 bg-gray-100 rounded-md py-1 px-2 shadow"
                        >
                            <x-icon name="chevron-left" size="12px"/> {{ __('Previous') }}
                        </a>
                    </div>

                    <div x-text="`${paginator.current_page}/${paginator.last_page}`" class="grow text-center text-sm font-medium"></div>

                    <div class="shrink-0">
                        <a 
                            x-show="paginator.current_page < paginator.last_page"
                            x-on:click="next()" 
                            class="flex items-center gap-2 text-gray-600 bg-gray-100 rounded-md py-1 px-2 shadow"
                        >
                            {{ __('Next') }} <x-icon name="chevron-right" size="12px"/>
                        </a>
                    </div>
                </div>

                <div x-show="!loading && list.length > 0">
                    <template x-for="opt in list">
                        <div
                            x-on:click="!opt.isGroup && select(opt)"
                            x-bind:class="{
                                'bg-gray-100': opt.isGroup,
                                'cursor-pointer hover:bg-slate-100': !opt.isGroup
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

                            <div 
                                x-bind:class="list.some(res => (res.isGroup)) && !opt.isGroup && 'ml-4'"
                                class="grow grid"
                            >
                                <div 
                                    x-text="opt.label" 
                                    x-bind:class="{
                                        'font-bold': opt.isGroup,
                                        'font-semibold': !opt.isGroup && opt.small,
                                    }"
                                    class="truncate"
                                ></div>
                                <div x-show="opt.small" x-text="opt.small" class="truncate font-medium text-sm text-gray-400"></div>
                            </div>

                            <div x-show="opt.badge || opt.remark" class="shrink-0 flex flex-col gap-1 items-end">
                                <div 
                                    x-text="opt.badge" 
                                    x-bind:class="['text-sm px-2 font-medium rounded-full shadow'].concat(opt.badge_colors)"
                                ></div>
                                <div x-text="opt.remark" class="text-sm font-medium text-gray-500"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="border-t grid divide-y">
                @isset($footlink)
                    <a 
                        href="{{ $footlink->attributes->get('href') }}"
                        class="p-4 text-center flex items-center justify-center gap-2 hover:bg-slate-100"
                    >
                        @if ($icon = $footlink->attributes->get('icon'))
                            <x-icon :name="$icon" size="14px"/>
                        @endif

                        @if ($label = $footlink->attributes->get('label')) {{ __($label) }}
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
    </div>
</x-form.field>
