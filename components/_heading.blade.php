@php
$size = $attributes->get('size', 'default');
$level = $attributes->get('level');
$subheading = $attributes->has('data-atom-subheading');
$el = $level ? "h{$level}" : 'div';

$classes = $attributes->classes()->add('[&:has(+[data-atom-subheading])]:mb-1.5 [[data-atom-subheading]+&]:mt-1.5');
if ($subheading) $classes->add('text-zinc-500');
else if ($size === 'xl') $classes->add('font-semibold');
else $classes->add('font-medium');

$styles = $attributes->styles();
if ($subheading || $size === 'default') $styles->add('font-size', '14px');
else if ($size === 'lg') $styles->add('font-size', '16px');
else if ($size === 'xl') $styles->add('font-size', '24px');
else if ($size) $styles->add('font-size', str($size)->finish('px'));

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
</{{ $el }}>