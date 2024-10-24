@php
$href = $attributes->get('href');
$icon = $attributes->get('icon');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$newtab = $attributes->get('newtab', false);

$classes = $attributes->classes()
    ->add('text-sky-600 underline decoration-dashed')
    ->add($icon ? 'inline-flex items-center gap-2' : '')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'ref' => $rel,
        'target' => $newtab ? '_blank' : null,
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
</a>
