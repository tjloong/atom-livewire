<div class="relative" 
    x-data="{
        show: false,
        open () {
            this.show = true
            this.$nextTick(() => floatPositioning(this.$refs.button, this.$refs.dd))
        },
        close () {
            this.show = false
        },
    }"
    x-on:click.away="close()">
    <button type="button" 
        x-ref="button"
        x-tooltip="{{ $attributes->get('tooltip') }}" 
        x-bind:class="show && 'bg-slate-100'"
        x-on:click="show ? close() : open()">
        <x-icon name="{{ $attributes->get('icon') }}"/>
    </button>
    
    <div x-ref="dd" x-show="show" x-transition class="dropdown">
        {{ $slot }}
    </div>
</div>