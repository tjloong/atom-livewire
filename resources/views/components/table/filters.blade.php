<div x-data="{ show: false }">
    @if ($count = $attributes->has('count')
        ? $attributes->get('count')
        : collect($this->filters)->filter()->values()->count())
        <div class="flex items-center bg-red-100 border border-red-100 text-red-500 font-medium text-sm rounded-full">
            <div  x-on:click="show = true" class="pl-2 cursor-pointer">
                {{ tr('common.label.filter-count', $count) }}
            </div>

            <div x-on:click="$wire.resetFilters()" class="shrink-0 px-2 rounded-full cursor-pointer hover:bg-red-500 hover:ml-2 hover:text-white">
                <x-icon name="xmark"/>
            </div>
        </div>
    @else
        <div
            x-tooltip="{{ tr('common.label.filter') }}"
            x-on:click.prevent="show = true"
            class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
            <x-icon name="sliders" class="m-auto"/>
        </div>
    @endif

    <div
        x-show="show"
        x-transition.opacity
        class="absolute inset-0 z-20">
        <div x-on:click.away="show = false" 
            class="absolute top-0 right-0 max-w-md w-full p-2">
            <div class="bg-white rounded-lg shadow-lg border">
                <div class="flex flex-col divide-y">
                    <div class="shrink-0 p-4">
                        <x-heading title="common.label.filter" sm>
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
