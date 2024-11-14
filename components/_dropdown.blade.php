@php
$locked = $attributes->get('locked', false);
$placement = $attributes->get('placement') ?? $attributes->get('align');

$classes = $attributes->classes()
    ->add('group/dropdown relative cursor-pointer')
    ;

$attrs = $attributes
    ->class($classes)
    ->except('align')
    ;
@endphp

<div x-data data-atom-dropdown {{ $attrs }}>
    <div
        data-anchor
        @if (!$locked)
        x-on:click.away="$refs.popover?.hidePopover()"
        @endif
        x-on:click="$refs.popover?.showPopover()">
        {{ $slot }}
    </div>

    @isset ($popover)
        <atom:popover :attributes="$popover->attributes">
            <atom:menu>
                {{ $popover }}
            </atom:menu>
        </atom:popover>
    @endisset
</div>