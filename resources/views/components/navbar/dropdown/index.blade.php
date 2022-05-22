<div x-data="{ open: false }" x-on:click.away="open = false" class="relative">
    <div
        x-on:click.prevent="open = true"
        {{ $attributes->merge(['class' => 'block py-1.5 px-3 text-center cursor-pointer font-medium hover:text-theme']) }}
    >
        {{ $slot }}
    </div>

    <div
        x-show="open"
        x-transition
        class="
            grid
            md:absolute md:z-10 md:w-max {{ $attributes->has('right') ? 'md:right-0' : '' }}
            md:bg-white md:drop-shadow-md md:rounded-md md:border
            md:py-2 md:min-w-[200px]
        "
    >
        {{ $dropdown }}
    </div>
</div>
