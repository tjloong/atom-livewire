<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div
        x-data="{
            show: false,
            text: null,
            value: @entangle($attributes->wire('model')),
            multiple: @js($multiple),
            clearOnOpen: @js($clearOnOpen),
            open () {
                if (this.clearOnOpen) this.value = null

                this.show = true
                this.$nextTick(() => {
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                    this.$refs.search && this.$refs.search.focus()
                })
            },
            close () {
                this.show = false
                this.text = null
            },
            select (val) {
                if (this.multiple) this.value.push(val)
                else this.value = val

                this.$nextTick(() => this.close())
            },
            remove (val) {
                this.value = this.value.filter(item => (`${item}` !== `${val}`))
            }
        }"
        x-on:click.away="close()"
        class="relative"
        {{ $attributes->wire('page') }}
        {{ $attributes->wire('search') }}
    >
        <div 
            x-ref="anchor" 
            x-on:click="open()" 
            x-bind:class="show && 'active'"
            class="form-input w-full {{ !$selected->count() ? 'select' : '' }}"
        >
            @if ($selected->count() && $multiple)
                <div class="flex flex-wrap gap-2">
                    @foreach ($selected as $sel)
                        <div class="bg-slate-200 rounded-md py-1 px-2 text-sm font-medium border flex items-center gap-2 max-w-[200px]">
                            <div class="grid">
                                <div class="truncate">{{ data_get($sel, 'label') }}</div>
                            </div>
                            <a x-on:click="remove('{{ data_get($sel, 'value') }}')" class="flex text-gray-500">
                                <x-icon name="xmark" size="12px"/>
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif ($selected->count())
                <div class="flex items-center justify-between gap-2">
                    <div class="grid">
                        <div class="truncate">{{ data_get($selected->first(), 'label') }}</div>
                    </div>
                    <a x-on:click="value = null" class="flex shrink-0 text-gray-500">
                        <x-icon name="xmark" size="16px" class="m-auto"/>
                    </a>
                </div>
            @elseif ($placeholder = $attributes->get('placeholder'))
                <div class="text-gray-400 grid">
                    <div class="truncate">{{ __($placeholder) }}</div>
                </div>
            @else
                <div class="text-gray-400 grid">
                    <div class="truncate">{{ __('Select '.($label ?? 'an option')) }}</div>
                </div>
            @endif
        </div>

        <div 
            x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-20 bg-white shadow-lg rounded-md border border-gray-300 overflow-hidden grid divide-y w-full min-w-[300px]"
        >
            @if (count($options) > 10)
                <div class="py-2 px-4 flex items-center gap-2 bg-gray-100">
                    <x-icon name="search" size="15px" class="text-gray-400"/>
                    <div class="grow">
                        <input type="text"
                            x-ref="search"
                            x-model="text"
                            x-on:input.debounce.400ms="$dispatch('search', text)"
                            placeholder="{{ __('Search') }}"
                            class="bg-transparent appearance-none border-0 p-0 focus:ring-0 w-full"
                        >
                    </div>
                </div>
            @endif

            <div class="{{ count($options) > 10 ? 'h-[250px] overflow-auto' : '' }}">
                <div class="grid divide-y">
                    @forelse ($options as $opt)
                        <div 
                            @if ($isCountries || !$attributes->wire('search')->value()) 
                                x-show="!text || '{{ data_get($opt, 'label') }}'.toLowerCase().includes(text.toLowerCase())"
                            @endif
                            x-on:click="select('{{ data_get($opt, 'value') }}')"
                            class="py-2 px-4 hover:bg-gray-100 cursor-pointer flex items-center justify-between"
                            data-option-value="{{ data_get($opt, 'value') }}"
                            data-option-label="{{ data_get($opt, 'label') }}"
                        >
                            <div class="grid">
                                @if ($isCountries)
                                    <div class="flex items-center gap-2">
                                        @if ($flag = data_get($opt, 'flag')) <img src="{{ $flag }}" class="w-4"> @endif
                                        <div class="font-medium">{{ data_get($opt, 'label') }}</div>
                                    </div>
                                @else
                                    <div class="font-medium truncate">{{ data_get($opt, 'label') }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center gap-2 py-2 px-4">
                            <x-icon name="folder-open" size="15px" class="text-gray-400"/>
                            <div class="font-medium text-gray-400">{{ __('List is empty') }}</div>
                        </div>
                    @endforelse

                    @if ($prevpage || $nextpage)
                        <div class="py-2 px-4 flex items-center justify-between gap-4">
                            <div>
                                @if ($prevpage)
                                    <a x-on:click="$dispatch('page', {{ $prevpage }})" class="flex items-center gap-2">
                                        <x-icon name="chevron-left" size="15px"/> {{ __('Previous') }}
                                    </a>
                                @endif
                            </div>

                            <div>
                                @if ($nextpage)
                                    <a x-on:click="$dispatch('page', {{ $nextpage }})" class="flex items-center gap-2">
                                        {{ __('Next') }} <x-icon name="chevron-right" size="15px"/>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-form.field>
