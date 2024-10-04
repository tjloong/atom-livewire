@php
$name = $attributes->get('name');
$label = $attributes->get('label') ?? str()->headline($name);
$inset = $attributes->get('inset');
$transparent = $attributes->get('transparent');

$classes = $attributes->classes()
    ->add('group/sheet absolute top-0 left-0 right-0 min-h-dvh')
    ->add('hidden opacity-0 transition-opacity duration-200 ease-in-out')
    ->add($inset ? '' : 'p-6 pb-20')
    ->add($transparent ? '' : 'bg-white')
    ->add('group-has-[[data-atom-breadcrumb].hidden]/panel:pt-0')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'wire:open' => $attributes->hasLike('x-on:open*') ? null : 'openSheet($event.detail)',
    ])
    ->except(['name', 'label', 'inset', 'transparent'])
    ;
@endphp

<div
    wire:ignore.self
    x-cloak
    x-data="sheet({ name: @js($name), label: @js($label) })"
    x-transition.opacity.duration.200
    x-on:sheet-show.window="show($event.detail)"
    x-on:sheet-back.window="back()"
    data-atom-sheet
    {{ $attrs }}>
    {{ $slot }}
</div>