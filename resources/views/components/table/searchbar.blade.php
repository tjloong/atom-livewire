@props([
    'total' => $attributes->get('total'),
    'search' => $attributes->get('search', true),
])

<div class="py-3 px-4">
    <div class="flex flex-wrap justify-between items-center gap-2">
        <div class="grow text-gray-800 flex items-end gap-1.5">
            @if (is_numeric($total))
                <div class="text-lg font-medium leading-snug">
                    {{ short_number($total) }}
                </div>

                <div class="text-gray-500">
                    {{ __('total rows') }}
                </div>
                
                @isset($maxRows) {{ $maxRows }}
                @else
                    <x-dropdown>
                        <x-slot:anchor>
                            <div class="bg-gray-200 rounded-full text-sm font-medium leading-6 px-2 flex items-center gap-2">
                                <div>
                                    <span x-data x-text="$wire.get('maxRows')"></span> / {{ __('page') }}
                                </div>
                                <x-icon name="chevron-down" size="10"/>
                            </div>
                        </x-slot:anchor>

                        <div class="flex flex-col divide-y">
                            @foreach ([50, 100, 150, 200, 500] as $n)
                                <x-dropdown.item 
                                    :label="$n.' / page'"
                                    x-on:click="$wire.set('maxRows', {{ $n }}); close()"/>
                            @endforeach
                        </div>
                    </x-dropdown>
                @endisset
            @endif
        </div>

        <div class="shrink-0 flex items-center gap-2">
            @if ($search)
                <div 
                    x-data="{
                        show: false,
                        focus: false,
                        text: null,
                        value: @entangle(($attributes->wire('model')->value() ?: 'filters.search')),
                        init () {
                            this.text = this.value
                            this.show = !empty(this.value)
                            this.$watch('text', () => this.value = this.text)
                        },
                        open () {
                            this.show = true
                            this.focus = true
                            this.$nextTick(() => this.$refs.text.focus())
                        },
                        close () {
                            if (empty(this.text)) {
                                this.show = false
                                this.focus = false
                            }
                        },
                    }"
                    x-on:click="open"
                    x-on:click.away="close">
                    <div
                        x-show="!show"
                        x-tooltip="Search"
                        class="cursor-pointer flex p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
                        <x-icon name="search"/>
                    </div>

                    <div 
                        x-show="show" 
                        x-on:click.away="focus = false"
                        x-bind:class="focus && 'active'"
                        class="form-input flex items-center gap-3">
                        <div class="shrink-0">
                            <x-icon name="search" class="text-gray-400"/>
                        </div>

                        <input type="text"
                            x-ref="text"
                            x-model.debounce.400ms="text"
                            class="form-input transparent grow"
                            placeholder="{{ __('atom::table.search.placeholder') }}">

                        <x-close x-show="!empty(text)" x-on:click.stop="text = null"/>
                    </div>
                </div>
            @endif

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
</div>