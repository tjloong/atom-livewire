@if ($attributes->get('disabled'))
    <div class="py-2 px-3 flex items-center gap-2 text-gray-400">
        @if ($icon = $attribute->get('icon'))
            <x-icon :name="$icon" :type="$attributes->get('icon-type') ?? 'regular'"/>
        @endif

        @if ($label = $attributes->get('label')) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </div>
@else
    <div 
        x-data="{ 
            itemName: @js($attributes->get('name')),
            itemHref: @js($attributes->get('href')),
            get active () { return value === this.itemName }
        }"
        x-on:click="
            if (itemHref) window.location = itemHref
            else select(itemName)
        "
        x-bind:class="{
            'font-bold text-theme-inverted bg-theme rounded-md md:border-r-2 md:border-theme-dark md:text-theme-dark md:bg-gray-200 md:rounded-r-none': active && show,
            'font-bold bg-white ring-theme ring-2 rounded-md md:border-r-2 md:border-theme-dark md:text-theme-dark md:bg-gray-200 md:rounded-r-none md:ring-0': active && !show,
            'hidden font-medium text-gray-600 hover:font-bold md:flex': !active && !show,
            'font-medium text-gray-600 hover:font-bold md:flex': !active && show,
        }"
        class="py-2 px-4 flex items-center gap-2 cursor-pointer"
        {{ $attributes->except(['name', 'active']) }}
    >
        @if ($icon = $attributes->get('icon'))
            <div
                x-bind:class="active ? 'md:text-theme' : 'md:text-gray-400'"
                class="shrink-0 flex items-center justify-center"
            >
                <x-icon :name="$icon" :type="$attributes->get('icon-type') ?? 'regular'" size="16px"/>
            </div>
        @elseif ($attributes->has('bullet'))
            <div
                x-bind:class="active 
                    ? 'w-2 h-2 bg-theme-dark ring-2 ring-offset-2 ring-theme mr-1' 
                    : 'w-3 h-3 border-2 border-gray-400'
                "
                class="shrink-0 rounded-full"
            ></div>
        @endif

        <div class="grow">
            @if ($label = $attributes->get('label')) {{ __($label) }}
            @else {{ $slot }}
            @endif
        </div>

        @if ($count = $attributes->get('count'))
            <div class="shrink-0">
                <div class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs">
                    {{ $count }}
                </div>
            </div>
        @endif 
        
        <div x-show="active && !show" class="shrink-0 flex items-center justify-center md:hidden">
            <x-icon name="chevron-down" size="15px"/>
        </div>
    </div>
@endif
