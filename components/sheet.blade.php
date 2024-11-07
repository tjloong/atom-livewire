@php
$name = $attributes->get('name');
$label = $attributes->get('label') ?? str()->headline($name);
$inset = $attributes->get('inset');
$breadcrumb = $attributes->get('breadcrumb', true);
$transparent = $attributes->get('transparent');

$classes = $attributes->classes()
    ->add('group/sheet fixed inset-0 overflow-auto')
    ->add('hidden opacity-0 transition-opacity duration-200 ease-in-out')
    ->add('group-has-[[data-atom-panel-navbar]]/panel:pt-20')
    ->add('lg:ml-64 lg:group-has-[[data-atom-panel-sidebar-show]]/panel:ml-0')
    ->add($inset ? '' : 'px-6 pb-20')
    ->add($transparent ? '' : 'bg-white')
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['name', 'label', 'inset', 'transparent'])
    ;
@endphp

<div
    wire:ignore.self
    x-cloak
    x-data="sheet({ name: @js($name), label: @js($label) })"
    x-transition.opacity.duration.200
    x-on:sheet-show.window="show($event.detail)"
    x-on:sheet-label.window="setLabel($event.detail)"
    x-on:sheet-back.window="back()"
    x-on:scroll="scroll()"
    data-atom-sheet="{{ $name }}"
    {{ $attrs }}>
    @if ($breadcrumb)
        <atom:breadcrumb>
            @isset ($actions) {{ $actions }} @endisset
        </atom:breadcrumb>
    @endif

    {{ $slot }}
</div>