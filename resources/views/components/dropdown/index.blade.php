<div
    x-cloak
    x-data="{ 
        show: false,
        open () {
            this.show = true
            this.$nextTick(() => floatDropdown(this.$refs.anchor, this.$refs.dd))
        },
        close () {
            this.show = false
        },
    }"
    x-on:click.away="close()"
    class="relative"
>
    <div x-ref="anchor" x-on:click="open()" {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 cursor-pointer',
    ]) }}>
        @isset($anchor) {{ $anchor }}
        @else
            @if ($icon = $attributes->get('icon')) <x-icon :name="$icon"/> @endif
            @if ($label = $attributes->get('label')) {{ __($label) }} @endif
            <x-icon name="chevron-down" size="12"/>
        @endisset
    </div>

    <div
        x-ref="dd"
        x-show="show"
        x-transition.opacity
        class="absolute z-20 w-full bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[250px] overflow-hidden"
    >
        {{ $slot }}
    </div>
</div>
