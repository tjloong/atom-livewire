@php
$total = $attributes->get('no-total') ? false : $attributes->get('total');
if (!is_numeric($total)) $total = $this->paginator ? format($this->paginator->total())->short() : false;
@endphp

<div class="py-3 px-4">
    <div class="flex flex-wrap justify-between items-center gap-2">
        <div class="shrink-0 text-gray-800 flex items-center gap-1.5">
            @if ($total !== false)
                <div class="text-lg font-medium leading-snug">
                    {{ $total }}
                </div>

                <div class="text-gray-500">
                    {{ tr('app.label.row', $total) }}
                </div>
            @endif

            @if(!$attributes->get('no-max-rows'))
                <x-button :label="$this->tableMaxRows.' / '.tr('app.label.page')" color="gray" invert xs dropdown="bottom-start">
                    <div class="flex flex-col divide-y text-sm">
                        @foreach ([50, 100, 150, 200, 500] as $n)
                            <x-dropdown.item :label="$n.' / '.tr('app.label.page')" wire:click="$set('tableMaxRows', {{ $n }})"/>
                        @endforeach
                    </div>
                </x-button>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if (!$attributes->get('no-search'))
                <div
                    x-data="{
                        text: null,
                        value: @entangle(($attributes->wire('model')->value() ?: 'filters.search')),
                        search () {
                            this.value = this.text
                        },
                    }"
                    x-init="text = value">
                    <x-input
                        x-model="text"
                        x-on:keydown.enter.prevent="search()"
                        x-on:clear="search()"
                        placeholder="app.label.search"
                        class="w-72">
                        <x-slot:button icon="search" x-on:click.stop="search()"></x-slot:button>
                    </x-input>
                </div>
            @endif

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
</div>