@php
$avatar = $attributes->get('avatar');

$size = $attributes->get('size', 'md');
$size = match ($size) {
    'xs' => 22,
    'sm' => 28,
    'md' => 32,
    'lg' => 40,
    default => $size,
};

$initial = trim(strip_tags($slot->toHtml()));
$initial = preg_replace('/\s+/', '', $initial) ?: '-';
$initial = $size === 'lg' ? substr($initial, 0, 2) : substr($initial, 0, 1);

$classes = $attributes->classes()
    ->add('bg-zinc-200 rounded-full overflow-hidden shadow-sm')
    ;

$styles = $attributes->styles()
    ->add('width', str($size)->finish('px'))
    ->add('height', str($size)->finish('px'))
    ->add('font-size', str($size * 0.55)->finish('px'))
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'style' => $styles,
    ])
    ->except(['avatar', 'size'])
    ;
@endphp

<figure {{ $attrs }}>
    @if (is_string($avatar))
        <img src="{{ $avatar }}" class="w-full h-full object-cover">
    @else
        <div class="w-full h-full flex items-center justify-center leading-none text-muted-more">
            @e($initial)
        </div>
    @endif
</figure>