@props([
    'icon' => $attributes->get('icon'),
    'label' => $attributes->get('label'),
    'size' => $attributes->get('size'),
    'button' => $attributes->get('button', true),
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
    {{ $attributes->class([
        $icon && !$label ? 'flex items-center justify-center' : null,
    ])->except(['icon', 'label']) }}
>
    @if (isset($anchor))
        <div x-ref="anchor" x-on:click="open()" x-on:click.away="close()">
            {{ $anchor }}
        </div>
    @elseif ($button)
        <x-button x-ref="anchor" x-on:click="open()" x-on:click.away="close()" :size="$size">
            <div class="flex items-center gap-2">
                @if ($icon) <x-icon :name="$icon"/> @endif
                @if ($label) {!! __($label) !!} @endif
                <x-icon name="chevron-down sm"/>
            </div>
        </x-button>
    @else
        <div x-ref="anchor" x-on:click="open()" x-on:click.away="close()" class="inline-flex items-center gap-2 cursor-pointer">
            @if ($icon) <x-icon :name="$icon"/> @endif
            @if ($label) {!! __($label) !!} @endif
            <x-icon name="chevron-down sm"/>
        </div>
    @endif

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
