@php
$file = $attributes->get('file');
$variant = $attributes->get('variant');
$alt = $attributes->get('alt') ?? $file?->name;
$size = $attributes->size();
$fit = pick([
    'object-cover' => $attributes->has('cover'),
    'object-contain' => $attributes->has('contain'),
    'object-fill' => $attributes->has('fill'),
    'object-scale-down' => $attributes->has('scale-down'),
]);

$url = $attributes->get('url') ?? $attributes->get('src') ?? (
    optional($file)->is_image ? pick([
        $file->endpoint_sm => $size === 'sm',
        $file->endpoint_md => $size === 'md',
        $file->endpoint => true,
    ]) : null
);

$except = ['file', 'size', 'cover', 'contain', 'fill', 'scale-down', 'url'];
@endphp

@if ($url)
<img {{ $attributes
    ->merge([
        'src' => $url,
        'alt' => $alt,
    ])
    ->class(['w-full h-full', $fit])
    ->except($except)
}}>
@endif
