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
    <div
        x-ref="anchor"
        x-tooltip.raw="{!! tr($tooltip) !!}"
        x-on:click="open()"
        class="py-1 px-2 cursor-pointer hover:bg-slate-100">
        <x-icon :name="$icon"/>
    </div>
    
    <div
        x-ref="dropdown"
        x-show="show"
        x-anchor.offset.6="$refs.anchor"
        x-transition.opacity.duration.100
        class="z-10 bg-white border border-gray-300 shadow-lg rounded-lg">
        {{ $slot }}
    </div>
</div>