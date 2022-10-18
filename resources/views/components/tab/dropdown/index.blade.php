<div
    x-cloak
    x-data="{
        show: false,
        active: false,
        activeLabel: null,
        init () {
            this.checkActive()
        },
        open () {
            this.show = true
            this.$nextTick(() => floatDropdown(this.$refs.anchor, this.$refs.dd))
        },
        close () {
            this.show = false
        },
        checkActive () {
            this.$nextTick(() => {
                const activeEl = $el.querySelector('.active')
                
                this.active = activeEl ? true : false
                this.activeLabel = activeEl
                    ? activeEl.querySelector('[data-label]').innerText
                    : null
            })

            this.close()
        },
    }"
    x-on:click.away="close()"
    x-on:select-tab.window="checkActive"
    class="shrink-0 cursor-pointer"
>
    <div 
        x-ref="anchor" 
        x-on:click="open()"
        x-bind:class="active && 'text-theme-dark font-bold border-b-2 border-theme-dark'"
        {{ $attributes->class([
            'inline-flex items-center gap-2 p-1',
            'font-medium text-gray-400 hover:text-gray-600',
        ]) }}
    >
        @isset($anchor) {{ $anchor }}
        @else
            @if ($icon = $attributes->get('icon')) <x-icon :name="$icon"/> @endif

            @if ($label = $attributes->get('label')) 
                <div x-show="active" x-text="activeLabel" class="grow"></div>
                <div x-show="!active" class="grow">{{ __($label) }}</div>
            @endif
            
            <x-icon name="chevron-down" size="12"/>
        @endisset
    </div>

    <div
        x-ref="dd"
        x-show="show"
        x-transition
        data-tab-dd
        class="absolute z-20 w-full bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[200px] py-1 overflow-hidden"
    >
        {{ $slot }}
    </div>
</div>
