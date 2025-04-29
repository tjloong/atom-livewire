@php
$src = $attributes->get('src');
$srcsm = $attributes->get('src-sm');
$icon = $attributes->get('icon', 'file');

$url = parse_url($src);
$urlpath = $url['path'];
$type = pick([
    'image' => str($urlpath)->endsWith(['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg', '.tiff']),
    'video' => str($urlpath)->endsWith(['.mp4', '.ogg', '.mpeg', '.avi']),
    'youtube' => str($urlpath)->startsWith(['/watch']),
    'icon' => !empty($icon),
]);

$attrs = $attributes->merge([
    'class' => match ($type) {
        'image', 'video' => 'w-full h-full object-contain',
        'youtube' => 'w-full h-full',
        'icon' => 'flex items-center justify-center w-full h-full text-muted',
        default => '',
    },
    ...($type === 'youtube' ? [
        'frameborder' => '0',
        'referrerpolicy' => 'strict-origin-when-cross-origin',
        'allowfullscreen' => true,
    ] : []),
    ...($type === 'video' ? [
        'controls' => true,
    ] : []),
])->except(['src', 'src-sm', 'icon']);
@endphp

@if ($type === 'image')
    @if ($srcsm)
        <object data="{!! $srcsm !!}" {{ $attrs }}>
            <img src="{!! $src !!}" {{ $attrs->only('class') }}>
        </object>
    @else
        <img src="{!! $src !!}" {{ $attrs }}>
    @endif
@elseif ($type === 'video')
    <video {{ $attrs }}>
        <source src="{{ $src }}" type="video/mp4">
    </video>
@elseif ($type === 'youtube')
    <iframe src="{{ $src }}" {{ $attrs }}></iframe>
@elseif ($type === 'icon')
    <div {{ $attrs }}>
        <atom:icon :name="$icon" size="64"/>
    </div>
@endif
