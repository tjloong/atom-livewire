@php
$name = $attributes->get('name');
$label = $attributes->get('label') ?? str()->headline($name);
$inset = $attributes->get('inset');
$breadcrumb = $attributes->get('breadcrumb', true);
$transparent = $attributes->get('transparent');

$classes = $attributes->classes()
    ->add('group/sheet absolute top-0 left-0 right-0 min-h-dvh')
    ->add('hidden opacity-0 transition-opacity duration-200 ease-in-out')
    ->add('group-has-[[data-atom-panel-navbar]]/panel:pt-20')
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
    data-atom-sheet="{{ $name }}"
    {{ $attrs }}>
    @if ($breadcrumb)
        <atom:breadcrumb/>
    @endif

    {{ $slot }}
</div>