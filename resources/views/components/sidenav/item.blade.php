@props([
    'value' => $attributes->get('value'),
    'name' => $attributes->get('name'),
    'href' => $attributes->get('href'),
    'label' => $attributes->get('label'),
    'count' => $attributes->get('count'),
    'active' => $attributes->get('active'),
    'status' => $attributes->get('status'),
    'statusColor' => $attributes->get('status-color'),
])

@if ($attributes->get('disabled'))
    <div class="py-2 px-3 flex items-center gap-2 text-gray-400">
        @isset ($icon) {{ $icon }}
        @elseif ($icon = $attributes->get('icon')) <x-icon :name="$icon ?? $label" size="16"/>
        @elseif ($attributes->has('bullet')) <div class="shrink-0 rounded-full w-3 h-3 border-2 border-gray-400"></div>
        @endif

        @if ($label) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </div>
@else
    <div 
        x-data="{
            get active () {
                return @js($active) 
                    || (!empty(value) && value === @js($value ?? $name))
                    || @js(str(url()->current())->startsWith($href))
            },
            select (val) {
                if (!empty(val)) value = val
                else if (!empty(@js($href))) window.location = @js($href)
            },
        }"
        x-on:click="select(@js($value ?? $name))"
        {{ $attributes->except(['name', 'active']) }}
    >
        {{-- Mobild view --}}
        <div x-show="active && !show" x-on:click.stop="show = true" class="py-2 px-4 flex items-center gap-3 cursor-pointer bg-white ring-theme ring-2 rounded-md md:hidden">
            <x-icon name="bars"/>
            <div class="grow font-bold">
                @if ($label) {{ __($label) }}
                @else {{ $slot }}
                @endif    
            </div>
            <x-icon name="chevron-down"/>
        </div>

        <div
            x-bind:class="{
                'font-bold text-theme-inverted bg-theme rounded-md md:border-r-2 md:border-theme-dark md:text-theme-dark md:bg-gray-200 md:rounded-r-none': active && show,
                'hidden font-bold rounded-md md:flex md:border-r-2 md:border-theme-dark md:text-theme-dark md:bg-gray-200 md:rounded-r-none md:ring-0': active && !show,
                'hidden font-medium text-gray-600 hover:font-bold md:flex': !active && !show,
                'font-medium text-gray-600 hover:font-bold md:flex': !active && show,
            }"
            class="py-2 px-4 flex items-center gap-2 cursor-pointer"
        >
            @isset($icon)
                <div class="shrink-0">
                    {{ $icon }}
                </div>
            @elseif ($icon = $attributes->get('icon'))
                <div
                    x-bind:class="active ? 'md:text-theme' : 'md:text-gray-400'"
                    class="shrink-0 flex items-center justify-center"
                >
                    <x-icon :name="$icon ?? $label" size="16"/>
                </div>
            @elseif ($attributes->has('bullet'))
                <div
                    x-bind:class="active 
                        ? 'w-2 h-2 bg-theme-dark ring-2 ring-offset-2 ring-theme mr-1' 
                        : 'w-3 h-3 border-2 border-gray-400'
                    "
                    class="shrink-0 rounded-full"
                ></div>
            @endisset

            <div class="grow">
                @if ($label) {{ __($label) }}
                @else {{ $slot }}
                @endif
            </div>

            @if ($status)
                <div class="shrink-0">
                    <x-badge :label="$status" :color="$statusColor"/>
                </div>
            @endif

            @if ($count)
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
    </div>
@endif
