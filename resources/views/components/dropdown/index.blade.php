@php
    $icon = $attributes->get('icon');
    $label = $attributes->get('label');
    $placement = $attributes->get('placement', 'bottom');
@endphp

<div x-cloak
    x-data="{
        show: false,
        open () {
            this.show = true
            this.$nextTick(() => floatDropdown(this.$refs.anchor, this.$refs.dropdown, @js($placement)))
        },
        close () {
            this.show = false
        },
    }"
    {{ $attributes->except(['icon', 'label', 'placement']) }}>
    <div x-ref="anchor" x-on:click="open()" x-on:click.away="close()" class="inline-block cursor-pointer">
        @if (isset($anchor)) {{ $anchor }}
        @else
            <div class="flex items-center gap-2">
                @if ($icon) <x-icon :name="$icon"/> @endif
                @if ($label) {!! tr($label) !!} @endif
                <x-icon name="chevron-down" class="text-sm"/>
            </div>
        @endif
    </div>

    <div x-ref="dropdown"
        x-show="show"
        x-transition
        class="absolute z-20 bg-white border rounded-md shadow-lg max-w-md min-w-[250px] overflow-hidden">
        {{ $slot }}
    </div>
</div>