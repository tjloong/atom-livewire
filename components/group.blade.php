@php
$type = $attributes->get('type');

$classes = $attributes->classes()
    ->add(match ($type) {
        'checkbox' => 'grid gap-2 [&>[data-atom-heading]]:mb-1',
        default => 'grid gap-6 [&>[data-atom-heading]]:-mb-3',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except('type')
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>
