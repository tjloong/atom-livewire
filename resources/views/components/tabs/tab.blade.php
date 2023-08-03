@props([
    'href' => $attributes->get('href'),
    'icon' => $attributes->get('icon'),
    'label' => $attributes->get('label'),
    'count' => $attributes->get('count'),
    'value' => $attributes->get('value'),
    'target' => $attributes->get('target', '_blank'),
])

<button type="button"
    @if ($href) x-on:click="window.open(@js($href), @js($target))"
    @else x-on:click="value = @js($value)"
    @endif
    class="relative grow transition-all rounded-md cursor-pointer"
>
    @if ($slot->isNotEmpty()) {{ $slot }}
    @else
        <div 
            class="relative inline-flex items-center justify-center px-3 py-1.5 gap-2"
            style="z-index: 2"
        >
            @if ($icon)
                <div class="shrink-0">
                    <x-icon :name="$icon"/>
                </div>
            @endif

            @if ($label)
                <div class="grow font-medium whitespace-nowrap">
                    {!! __($label) !!}
                </div>
            @endif

            @if ($count)
                <div class="shrink-0 flex items-center justify-center">
                    <x-badge :label="$count" color="blue" size="xs"/>
                </div>
            @endif
        </div>
    @endif

    <div 
        x-show="value === @js($value)" 
        x-transition.duration.300ms
        class="absolute inset-0 bg-white rounded-md shadow-sm"
        style="z-index: 1"
    ></div>
</button>