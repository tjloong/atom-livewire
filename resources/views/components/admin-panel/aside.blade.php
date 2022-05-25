@if ($attributes->has('can') && !auth()->user()->can($attributes->get('can')))
    {{-- No permission --}}
@elseif ($href || ($route && Route::has($route)))
    <a href="{{ $href ?: route($route, $params) }}" class="pl-2" {{ $isActive ? 'data-active' : '' }}>
        <div
            class="
                flex items-center gap-3 rounded-l-md px-4 py-2.5 text-white
                {{ $isActive ? 'font-semibold bg-white/20 border-r-8 border-theme' : 'font-medium hover:bg-white/10' }}
            "
        >
            @if ($icon = $attributes->get('icon'))
                <x-icon name="{{ $icon }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="18px"/>
            @elseif ($logo = $attributes->get('logo'))
                <x-logo :src="$logo" class="brightness-0 invert" style="width: 20px; height: 20px;"/>
            @endif

            <div class="truncate">
                @if ($label = $attributes->get('label')) {{ __($label) }}
                @else {{ $slot }}
                @endif
            </div>
        </div>
    </a>
@elseif (!$href && !$route)
    <div
        x-data="{ active: false, open: false }"
        x-init="active = $refs.subitems.querySelectorAll('[data-active]').length > 0"
    >
        <div x-on:click="open = true" class="cursor-pointer pl-2">
            <div
                x-bind:class="active && 'bg-white/10'"
                class="flex items-center gap-3 rounded-l-md px-4 py-2.5 text-white"
            >
                @if ($icon = $attributes->get('icon'))
                    <x-icon name="{{ $icon }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="20px"/>
                @elseif ($logo = $attributes->get('logo'))
                    <x-logo :src="$logo" class="brightness-0 invert" style="width: 20px; height: 20px;"/>
                @endif

                <div class="flex-grow truncate">
                    @if ($label = $attributes->get('label')) {{ __($label) }}
                    @else {{ $slot }}
                    @endif
                </div>
                
                <x-icon name="chevron-down"/>
            </div>
        </div>

        @if (isset($subitems) && $subitems->isNotEmpty())
            <div
                x-ref="subitems"
                x-show="active || open"
                x-on:click.away="open = false" 
                class="bg-gray-900 text-gray-300 grid py-1.5 pl-4"
            >
                {{ $subitems }}
            </div>
        @endif
    </div>
@endif
