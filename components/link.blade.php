@php
$href = $attributes->get('href');
$icon = $attributes->get('icon');
$iconsuffix = $attributes->get('icon-suffix');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$newtab = $attributes->get('newtab', false);

$classes = $attributes->classes()
    ->add('text-sky-600 underline decoration-dotted')
    ->add($icon || $iconsuffix ? 'inline-flex items-center gap-2' : '')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'ref' => $rel,
        'target' => $newtab ? '_blank' : null,
        'aria-label' => strip_tags($slot->toHtml()),
    ])
    ;
@endphp

<a {{ $attrs }}>
    @if ($icon)
        <atom:icon :name="$icon" class="shrink-0"/>
    @endif

    @if ($slot->isEmpty())    
        {{ $href }}
    @else
        {{ $slot }}
    @endif

    @if ($iconsuffix)
        <atom:icon :name="$iconsuffix" class="shrink-0"/>
    @endif
</a>
