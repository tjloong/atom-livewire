<div 
    x-data
    class="flex items-center py-2 px-3 rounded-md gap-2 shadow w-full {{ $attributes->get('class', 'bg-gray-100') }}"
>
    <x-icon name="search" size="18px" class="text-gray-400"/>

    <input
        type="text"
        class="w-full appearance-none p-0 border-0 bg-transparent focus:ring-0"
        wire:model.debounce.250ms="search"
        {{ $attributes->merge(['placeholder' => 'Search']) }}
    >

    <a 
        x-show="$wire.get('search')"
        class="flex text-gray-600"
        x-on:click="$wire.set('search', null)"
    >
        <x-icon name="xmark" size="15px" class="m-auto"/>
    </a>
</div>
