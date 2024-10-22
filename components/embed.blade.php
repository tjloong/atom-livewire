@php
$file = $attributes->get('file');
$url = $attributes->get('url') ?? $file?->endpoint;
$icon = $attributes->get('icon') ?? $file?->icon;

$type = pick([
    'image' => $file?->is_image || str($url)->endsWith(['.jpg', '.jpeg', '.png', '.webp', '.gif', '.svg', '.tiff']),
    'video' => $file?->is_video || str($url)->endsWith(['.mp4', '.ogg', '.mpeg', '.avi']),
    'youtube' => $file?->is_youtube || str($url)->startsWith(['https://www.youtube.com', 'https://youtube.com']),
    'icon' => !empty($icon),
]);
@endphp

@if ($type === 'image')
    <img src="{{ $url }}" class="w-full h-full object-contain">
@elseif ($type === 'video')
    <video class="w-full h-full object-contain" controls>
        <source src="{{ $url }}" type="video/mp4">
    </video>
@elseif ($type === 'youtube')
    <iframe src="https://www.youtube.com/embed/g5PBtcrm7Aw"
        frameborder="0"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen
        class="w-full h-full">
    </iframe>
@elseif ($type === 'icon')
    <div class="flex items-center justify-center w-full h-full text-muted">
        <atom:icon :name="$icon" size="64"/>
    </div>
@endif
