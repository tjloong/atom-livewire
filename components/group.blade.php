@php
$type = $attributes->get('type');
$gap = $attributes->get('gap');

$classes = $attributes->classes();

if ($type === 'checkbox') {
    $classes->add('group/group flex flex-col gap-2 [&>[data-atom-heading]]:mb-1');
}
elseif ($type === 'buttons') {
    if ($gap) $classes->add('group/group flex items-center flex-wrap gap-3');
    else {
        $classes = $attributes->classes()
            ->add('group/group flex items-center *:rounded-none')
            ->add('*:-ml-px first:*:ml-0')
            ->add('first:*:rounded-l-md last:*:rounded-r-md')
            ;
    }
}
elseif ($type === 'avatars') {
    $classes->add('group/group flex items-center *:-ml-2 first:*:-ml-0');
}
else {
    $classes->add('group/group flex flex-col gap-6 [&>[data-atom-heading]]:-mb-3');
}

$attrs = $attributes
    ->class($classes)
    ->except('type')
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>
