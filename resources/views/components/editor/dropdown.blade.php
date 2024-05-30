@php
$icon = $attributes->get('icon');
$tooltip = $attributes->get('tooltip');
@endphp

<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false },
    }"
    x-on:click.away="close()">
    <button type="button" 
        x-ref="anchor"
        x-tooltip.raw="{!! $tooltip !!}" 
        x-bind:class="show && 'bg-slate-100'"
        x-on:click="open()">
        <x-icon :name="$icon"/>
    </button>
    
    <div
        x-ref="dropdown"
        x-show="show"
        x-anchor.offset.6="$refs.anchor"
        x-transition.opacity.duration.100
        class="z-10 bg-white border border-gray-300 shadow-md rounded-md">
        {{ $slot }}
    </div>
</div>