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
    x-cloak
    x-data="dropdown(@js($align))"
    x-on:click="open()"
    x-on:click.away="close()"
    data-atom-dropdown
    {{ $attrs }}>
    {{ $slot }}
</div>