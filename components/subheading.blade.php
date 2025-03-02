@php
$classes = $attributes->classes()
    ->add('group-[]/panel-sidebar:px-3')
    ->add('group-[]/panel-sidebar:mt-4')
    ->add('group-[]/panel-sidebar:text-sm')
    ->add('group-[]/panel-sidebar:font-medium');

$attrs = $attributes->class($classes);
@endphp

<atom:_heading data-atom-subheading :attributes="$attrs">
    {{ $slot }}
</atom:_heading>
