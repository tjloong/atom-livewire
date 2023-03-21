@props([
    'total' => $attributes->get('total'),
    'placeholder' => $attributes->get('placeholder', 'Search'),
    'search' => $attributes->get('search', true),
    'wire' => $attributes->wire('model')->value() ?: 'filters.search',
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

                        @foreach ([50, 100, 150, 200, 500] as $n)
                            <x-dropdown.item 
                                :label="$n.' / page'"
                                x-on:click="$wire.set('maxRows', {{ $n }}); close()"
                                class="cursor-pointer"
                            />
                        @endforeach
                    </x-dropdown>
                @endisset
            @endif
        </div>

        <div class="shrink-0 flex items-center gap-2">
            @if ($search)
                <div x-data="{ show: !empty($wire.get(@js($wire))) }">
                    <div
                        x-show="!show"
                        x-tooltip="Search"
                        x-on:click="show = true" 
                        class="cursor-pointer flex p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200"
                    >
                        <x-icon name="search"/>
                    </div>

                    <div x-show="show" x-transition>
                        <x-form.text :label="false"
                            prefix="icon:search"
                            :placeholder="$placeholder"
                            wire:model.debounce.300ms="{{ $wire }}"
                        >
                            <x-slot:postfix>
                                <x-close
                                    x-show="$wire.get('{{ $wire }}')"
                                    x-on:click="$wire.set('{{ $wire }}', null)"
                                />
                            </x-slot:postfix>
                        </x-form.text>
                    </div>
                </div>
            @endif

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
</div>