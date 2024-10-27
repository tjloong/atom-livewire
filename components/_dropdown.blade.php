@php
$align = $attributes->get('align', 'left');

$classes = $attributes->classes()
    ->add('group/dropdown relative cursor-pointer')
    ;

$attrs = $attributes
    ->class($classes)
    ->except('align')
    ;
@endphp

<div
    wire:ignore.self
    x-cloak
    x-data="dropdown(@js($align))"
    data-atom-dropdown
    {{ $attrs }}>
    <div
        x-ref="trigger"
        x-on:click="open()"
        x-on:click.away="close()">
        {{ $slot }}
    </div>

    @isset ($content)
        <div
            x-ref="content"
            x-show="visible"
            x-transition.duration.200
            class="absolute left-0 z-10">
            <atom:menu>
                {{ $content }}
            </atom:menu>
        </div>
    @endisset
</div>