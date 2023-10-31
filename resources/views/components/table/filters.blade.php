<div x-data="{ show: false }">
    @if ($count = $attributes->has('count')
        ? $attributes->get('count')
        : collect($this->filters)->filter()->values()->count())
        <div
            x-on:click.prevent="show = true" 
            class="cursor-pointer text-xs bg-red-500 text-white font-medium rounded-full px-2 py-0.5 flex items-center justify-center gap-3">
            {{ trans_choice('atom::common.label.filter-count', $count) }}
            <x-icon name="xmark" x-on:click.stop="$wire.resetFilters()"/>
        </div>
    @else
        <div
            x-tooltip="{{ __('atom::common.label.filter') }}"
            x-on:click.prevent="show = true"
            class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
            <x-icon name="sliders" class="m-auto"/>
        </div>
    @endif

    <div
        x-show="show"
        x-transition.opacity
        class="absolute inset-0 z-10">
        <div x-on:click.away="show = false" 
            class="absolute top-0 right-0 max-w-md w-full p-2">
            <div class="bg-white rounded-lg shadow-lg border">
                <div class="flex flex-col divide-y">
                    <div class="shrink-0 p-4">
                        <x-heading title="atom::common.label.filter" sm>
                            <x-close x-on:click="show = false"/>
                        </x-heading>
                    </div>
    
                    <div class="grow">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
