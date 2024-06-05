@php
$file = $attributes->get('file');
$variant = $attributes->get('variant');
$alt = $attributes->get('alt') ?? $file?->name;
$size = $attributes->get('size');
$fit = pick([
    'object-cover' => $attributes->has('cover'),
    'object-contain' => $attributes->has('contain'),
    'object-fill' => $attributes->has('fill'),
    'object-scale-down' => $attributes->has('scale-down'),
]);

$variant = pick([
    'sm' => $attributes->has('sm'),
    'md' => $attributes->has('md'),
]);

$url = $attributes->get('url') ?? $attributes->get('src') ?? (
    optional($file)->is_image ? collect([$file->endpoint, $variant])->filter()->join('&') : null
);

$except = ['class', 'file', 'variant', 'alt', 'size', 'cover', 'contain', 'fill', 'scale-down', 'url', 'src'];
@endphp

@if ($url)
<figure class="{{ $attributes->get('class', 'w-full h-full') }}" {{ $attributes->except($except) }}>
    <img src="{!! $url !!}" alt="{!! $alt !!}" class="w-full h-full {{ $fit }}" {{ $attributes->only(['width', 'height']) }}>
</figure>
@endif
