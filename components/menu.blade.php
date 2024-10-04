@php
$classes = $attributes->classes()
    ->add('group/menu min-w-56 p-1 rounded-lg shadow-sm border border-zinc-200 bg-white')
    ;

$attrs = $attributes->class($classes);
@endphp

<div {{ $attrs }} data-atom-menu>
    {{ $slot }}
</div>