@php
$classes = $attributes->classes()
    ->add('group/menu p-1 rounded-lg shadow-sm border border-zinc-200 bg-white min-w-56 max-w-screen-md')
    ;

$attrs = $attributes->class($classes);
@endphp

<div {{ $attrs }} data-atom-menu>
    {{ $slot }}
</div>