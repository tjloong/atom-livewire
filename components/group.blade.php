@php
$type = $attributes->get('type');
$gap = $attributes->get('gap');

$classes = $attributes->classes();

if ($type === 'checkbox') {
    $classes->add('grid gap-2 [&>[data-atom-heading]]:mb-1');
}
elseif ($type === 'buttons') {
    if ($gap) $classes->add('flex items-center flex-wrap gap-3');
    else {
        $classes = $attributes->classes()
            ->add('flex items-center *:rounded-none')
            ->add('*:-ml-px first:*:ml-0')
            ->add('first:*:rounded-l-md last:*:rounded-r-md')
            ;
    }
}
else {
    $classes->add('grid gap-6 [&>[data-atom-heading]]:-mb-3');
}

$attrs = $attributes
    ->class($classes)
    ->except('type')
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>
