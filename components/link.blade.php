@php
$href = $attributes->get('href');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$newtab = $attributes->get('newtab', false);

$classes = $attributes->classes()
    ->add('text-sky-600 underline decoration-dashed')
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
    @if ($slot->isEmpty())
        {{ $href }}
    @else
        {{ $slot }}
    @endif
</a>
