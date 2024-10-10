@php
$classes = $attributes->classes()
    ->add('group/menu p-1 rounded-lg shadow-sm border border-zinc-200 bg-white')
    ->add('[[data-atom-dropdown]>&]:min-w-56 [[data-atom-dropdown]>&]:absolute [[data-atom-dropdown]>&]:z-10')
    ->add('[[data-atom-dropdown]>&]:transition-opacity [[data-atom-dropdown]>&]:duration-75')
    ;

$attrs = $attributes->class($classes);
@endphp

<div {{ $attrs }} data-atom-menu>
    {{ $slot }}
</div>