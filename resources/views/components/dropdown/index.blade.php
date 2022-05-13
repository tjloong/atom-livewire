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
    @if ($label = $attributes->get('label'))
        <a x-ref="anchor" x-on:click="open()">
            {{ __($label) }}
        </a>
    @elseif (isset($anchor))
        <div x-ref="anchor" x-on:click="open()" class="cursor-pointer">
            {{ $anchor }}
        </div>
    @endif

    <div
        x-ref="dd"
        x-show="show"
        x-transition.opacity
        class="absolute z-20 w-full bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[250px] py-1"
    >
        {{ $slot }}
    </div>
</div>
