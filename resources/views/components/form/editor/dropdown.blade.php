@php
    $placement = $attributes->get('placement');
    $directive = $placement ? 'x-anchor.'.$placement : 'x-anchor';
@endphp

<div x-cloak x-data="{ open: false }" x-on:click.away="open = false">
    <button type="button" 
        x-ref="button"
        x-tooltip.raw="{{ $attributes->get('tooltip') }}" 
        x-bind:class="show && 'bg-slate-100'"
        x-on:click="show ? close() : open()">
        <x-icon name="{{ $attributes->get('icon') }}"/>
    </button>
    
    <div
        x-ref="dropdown"
        x-show="open"
        {{ $directive }}="$refs.button"
        x-transition.opacity
        class="dropdown">
        {{ $slot }}
    </div>
</div>