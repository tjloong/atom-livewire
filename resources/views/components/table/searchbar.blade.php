@props([
    'total' => $attributes->get('total'),
    'search' => $attributes->get('search', true),
])

<div class="py-3 px-4">
    <div class="flex flex-wrap justify-between items-center gap-2">
        <div class="shrink-0 text-gray-800 flex items-end gap-1.5">
            @if (is_numeric($total))
                <div class="text-lg font-medium leading-snug">
                    {{ short_number($total) }}
                </div>

                <div class="text-gray-500">
                    {{ __('total rows') }}
                </div>
                
                @isset($tableMaxRows) 
                    {{ $tableMaxRows }}
                @else
                    <x-dropdown>
                        <x-slot:anchor>
                            <div class="bg-gray-200 rounded-full text-sm font-medium leading-6 px-2 flex items-center gap-2 cursor-pointer">
                                <div>
                                    <span x-data x-text="$wire.get('tableMaxRows')"></span> / {{ __('page') }}
                                </div>
                                <x-icon name="chevron-down" size="10"/>
                            </div>
                        </x-slot:anchor>

                        <div class="flex flex-col divide-y">
                            @foreach ([50, 100, 150, 200, 500] as $n)
                                <x-dropdown.item 
                                    :label="$n.' / page'"
                                    x-on:click="$wire.set('tableMaxRows', {{ $n }}); close()"/>
                            @endforeach
                        </div>
                    </x-dropdown>
                @endisset
            @endif
        </div>

        <div class="grow flex items-center gap-2">
            @if ($search)
                <div 
                    x-data="{
                        show: false,
                        text: null,
                        value: @entangle(($attributes->wire('model')->value() ?: 'filters.search')),
                        init () {
                            this.text = this.value
                            this.show = !empty(this.value)
                        },
                        open () {
                            this.show = true
                            this.$nextTick(() => this.$refs.text.focus())
                        },
                        close () {
                            if (empty(this.text)) {
                                this.show = false
                            }
                        },
                        search () {
                            this.value = this.text
                        },
                    }"
                    x-on:click="open"
                    x-on:click.away="close"
                    class="md:grow md:flex md:items-center md:justify-end cursor-pointer">
                    <div
                        x-show="!show"
                        x-tooltip="{{ tr('common.label.search') }}"
                        class="cursor-pointer flex divide-x p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
                        <x-icon name="search"/>
                    </div>

                    <div 
                        x-show="show" 
                        x-on:click.away="show = false"
                        class="flex items-center divide-x divide-gray-300 border border-gray-300 rounded-md">
                        <div class="grow py-1.5 px-3 flex items-center gap-3">
                            <input type="text"
                                x-ref="text"
                                x-model="text"
                                x-on:keydown.enter.stop="search"
                                class="grow transparent"
                                placeholder="{{ __('atom::common.label.search') }}">

                            <div class="shrink-0">
                                <x-close x-show="!empty(text)" x-on:click.stop="text = null; search()"/>
                            </div>
                        </div>

                        <div class="shrink-0 flex flex-col">
                            <div class="grow py-1.5 px-3">
                                <button type="button" 
                                    x-on:click.stop="search"
                                    class="flex items-center justify-center text-gray-500">
                                    <x-icon name="search"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
</div>