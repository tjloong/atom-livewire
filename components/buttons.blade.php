@php
$classes = $attributes->classes()
    ->add('flex items-center *:rounded-none')
    ->add('*:-ml-px first:*:ml-0')
    ->add('first:*:rounded-l-md last:*:rounded-r-md')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'data-atom-buttons' => true,
    ])
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>