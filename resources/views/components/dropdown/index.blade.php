@props([
    'icon' => $attributes->get('icon'),
    'label' => $attributes->get('label'),
    'placement' => $attributes->get('placement', 'bottom'),
])

<div
    x-cloak
    x-data="{ 
        show: false,
        open () {
            this.show = true
            this.$nextTick(() => floatDropdown(this.$refs.anchor, this.$refs.dd, @js($placement)))
        },
        close () {
            this.show = false
        },
    }"
    x-on:click.away="close()"
    {{ $attributes->class([
        $icon && !$label ? 'flex items-center justify-center' : null,
    ])->except(['icon', 'label']) }}
>
    @isset($anchor)
        <div x-ref="anchor" x-on:click="open()" {{ $anchor->attributes->class([
            'inline-flex items-center justify-center gap-2 cursor-pointer',
        ]) }}>
            {{ $anchor }}
        </div>
    @else
        <div x-ref="anchor" x-on:click="open()" class="inline-flex items-center gap-2 cursor-pointer">
            @if ($icon) <x-icon :name="$icon"/> @endif
            @if ($label) 
                {!! __($label) !!} 
                <x-icon name="chevron-down" size="12"/>
            @endif
        </div>
    @endisset

    @if ($slot->isNotEmpty())
        <div
            x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-20 w-max bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[250px] overflow-hidden"
        >
            {{ $slot }}
        </div>
    @elseif (isset($items))
        <div
            x-ref="dd"
            x-show="show"
            x-transition.opacity
            {{ $items->attributes->merge([
                'class' => 'absolute z-20 w-max bg-white border border-gray-300 shadow-lg rounded-md max-w-md',
            ]) }}
        >
            {{ $items }}
        </div>
    @endif
</div>
