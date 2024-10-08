@php
$classes = $attributes->classes()
    ->add('block')
    ->add('[&>[data-atom-label]]:mb-2')
    ->add('[&>[data-atom-label]:has(+[data-atom-caption])]:mb-2')
    ->add('[&>[data-atom-label]+[data-atom-caption]]:mt-0')
    ->add('[&>[data-atom-label]+[data-atom-caption]]:mb-3')
    ->add('[&>*:not([data-atom-label])+[data-atom-caption]]:mt-3')
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['badge'])
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>