@php
$type = $attributes->get('type');

$classes = $attributes->classes()
    ->add('grid')
    ->add(match ($type) {
        'checkbox' => 'gap-2',
        default => 'gap-6',
    })
    ->add('[&>[data-atom-heading]]:-mb-3')
    ;

$attrs = $attributes
    ->class($classes)
    ->except('type')
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>
