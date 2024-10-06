@if ($slot->isEmpty())
    <div
        x-data
        x-tooltip="{{ js(t('export')) }}"
        wire:click.prevent="{{ $attributes->get('callback', 'export') }}"
        class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
        <x-icon name="export" class="m-auto"/>
    </div>
@else
    <div
        x-data="{ show: false }"
        x-on:click.away="show = false"
        class="relative">
        <div x-ref="anchor"
            x-on:click="show = true"
            class="p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
            <x-icon name="export" class="m-auto"/>
        </div>

        <div x-ref="dd"
            x-show="show"
            x-transition
            class="absolute right-0 z-20 bg-white border rounded-lg shadow w-max">
            {{ $slot }}
        </div>
    </div>
@endif
