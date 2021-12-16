@if ($attributes->has('dropdown'))
    <div x-data="{ open: false }" x-on:click.away="open = false" class="relative">
        <a 
            x-on:click.prevent="open = true"
            {{ $attributes->merge(['class' => 'block py-1.5 px-3 text-center text-gray-800 font-medium hover:text-theme']) }}
        >
            {{ $slot }}
        </a>

        <div
            x-show="open"
            x-transition
            class="
                grid 
                md:absolute md:z-10 md:right-0 md:w-max 
                md:bg-white md:drop-shadow-md md:rounded-md md:border
                md:py-2 md:min-w-[200px]
            "
        >
            {{ $dropdown }}
        </div>
    </div>
@elseif ($attributes->has('dropdown-item'))
    <a {{ $attributes->merge([
        'class' => '
            py-1.5 px-3 text-center text-sm text-gray-500 font-medium hover:text-theme 
            md:text-left md:text-gray-800 md:hover:bg-gray-100
        '
    ]) }}>
        {{ $slot }}
    </a>
@else
    <a {{ $attributes->merge(['class' => 'py-1.5 px-3 text-center text-gray-800 font-medium hover:text-theme']) }}>
        {{ $slot }}
    </a>
@endif
