@php
$size = $attributes->get('size', 'default');
$level = $attributes->get('level');
$subheading = $attributes->has('data-atom-subheading');
$el = $level ? "h{$level}" : 'div';

$classes = $attributes->classes();
$styles = $attributes->styles();

if ($subheading) {
    $classes->add('text-zinc-500 group-[]/menu:my-2 group-[]/menu:mx-3');

    if ($size === 'sm') $classes->add('text-sm');
    else if ($size === 'xs') $classes->add('text-xs');
}
else {
    $classes
        ->add('[&:has(+[data-atom-subheading])]:mb-1.5 [[data-atom-subheading]+&]:mt-1.5')
        ->add('[[data-atom-sheet]>&]:mb-5')
        ->add('[[data-atom-card]>&]:mb-4')
        ->add('[&:has([data-atom-heading-actions])]:flex [&:has([data-atom-heading-actions])]:flex-wrap')
        ->add('[&:has([data-atom-heading-actions])]:items-center [&:has([data-atom-heading-actions])]:justify-between')
        ;

    if ($size === 'default') $classes->add('font-medium text-base');
    else if ($size === 'lg') $classes->add('font-medium text-lg');
    else if ($size === 'xl') $classes->add('font-medium text-xl');
    else $classes->add('font-medium');
}

if (!in_array($size, ['default', 'lg', 'xl', 'sm', 'xs']) && $size) {
    $styles->add('font-size', str($size)->finish('px'));
}

$attrs = $attributes
    ->class($classes)
    ->merge([
        'style' => $styles,
        'data-atom-heading' => !$subheading,
    ])
    ->except(['size', 'level']);
@endphp

<{{ $el }} {{ $attrs }}>
    {{ $slot }}

    @isset ($actions)
        <div class="flex items-center gap-2 flex-wrap" data-atom-heading-actions>
            {{ $actions }}
        </div>
    @endisset
</{{ $el }}>