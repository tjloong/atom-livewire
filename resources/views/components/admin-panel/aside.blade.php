@props([
    'icon' => $attributes->get('icon'),
    'logo' => $attributes->get('logo'),
    'label' => $attributes->get('label'),
    'permitted' => !$attributes->has('can') || (
        $attributes->has('can') 
        && auth()->user() 
        && auth()->user()->can($attributes->get('can'))
    ),
])

@if ($permitted && (
    (isset($subitems) && $subitems->isNotEmpty())
    || (!isset($subitems))
))
    @if ($href || ($route && has_route($route)))
        <a href="{{ $href ?: route($route, $params) }}" class="pl-2" {{ $isActive ? 'data-active' : '' }}>
            <div
                class="
                    flex items-center gap-3 rounded-l-md px-4 py-2.5 text-white
                    {{ $isActive ? 'font-semibold bg-white/20 border-r-8 border-theme' : 'font-medium hover:bg-white/10' }}
                "
            >
                @if ($logo)
                    <x-logo :src="$logo" class="brightness-0 invert opacity-70" style="width: 20px; height: 20px;"/>
                @elseif ($icon !== false)
                    <x-icon :name="$icon ?? $label" class="opacity-70 w-4"/>
                @endif

                <div class="truncate">
                    @if ($label) {{ __($label) }}
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
                    class="flex items-center gap-3 rounded-l-md px-4 py-2.5 font-medium text-white"
                >
                    @if ($logo)
                        <x-logo :src="$logo" size="16" class="brightness-0 invert opacity-70"/>
                    @elseif ($icon !== false)
                        <x-icon :name="$icon ?? $label" class="opacity-70 w-4"/>
                    @endif

                    <div class="grow truncate">
                        @if ($label) {{ __($label) }}
                        @else {{ $slot }}
                        @endif
                    </div>
                    
                    <x-icon name="chevron-down" size="12px"/>
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
@endif
