<div x-data="{
    open () {
        $el.querySelector('#table-filters').dispatchEvent(
            new Event('open', { bubble: false })
        )
    },
}">
    @if ($count = $attributes->has('count')
        ? $attributes->get('count')
        : collect($this->filters)->filter()->values()->count())
        <div
            x-on:click.prevent="open" 
            class="cursor-pointer text-xs bg-red-500 text-white font-medium rounded-full px-2 py-0.5 flex items-center justify-center gap-3">
            {{ trans_choice('atom::table.filters.count', $count) }}
            <x-icon name="xmark" x-on:click.stop="$wire.resetFilters()"/>
        </div>
    @else
        <div
            x-tooltip="Filters"
            x-on:click.prevent="open"
            class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
            <x-icon name="sliders" class="m-auto"/>
        </div>
    @endif

    <x-drawer id="table-filters" class="max-w-lg">
        <x-slot:heading title="Filters"></x-slot:heading>
        {{ $slot }}
    </x-drawer>
</div>
