@php
$href = $attributes->get('href');
$name = $attributes->get('name')
    ?? collect($attributes->getAttributes())
        ->keys()
        ->reject(fn($key) => in_array($key, ['class', 'style', 'size', 'href', 'alt']))
        ->first()
    ?? 'logo';

$alt = $attributes->get('alt') ?? str($name)->headline();
$logo = atom()->logo($name);

$el = $href ? 'a' : 'figure';
$attrs = $attributes
    ->class(['*:w-full *:h-full *:object-contain'])
    ->merge(['href' => $href])
    ->except(['name', 'alt']);
@endphp

@if ($logo)
    <{{ $el }} {{ $attrs }}>
        @if (str($logo)->startsWith('<svg'))
            {!! $logo !!}
        @else
            <img
                src="{{ $logo }}"
                width="512"
                height="512"
                alt="{{ $alt }}">
        @endif
    </{{ $el }}>
@endif