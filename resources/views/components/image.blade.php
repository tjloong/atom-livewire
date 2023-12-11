@php
    $alt = $attributes->get('alt');
    $color = $attributes->get('color', '#64748b');
    $fit = $attributes->get('fit', 'cover');
    $isAvatar = $attributes->get('avatar', false);
    $size = explode('x', $attributes->get('size'));
    $width = $attributes->get('width') ?? (data_get($size, 0) ?: ($isAvatar ? 40 : '100%'));
    $height = $attributes->get('height') ?? (data_get($size, 1) ?: ($isAvatar ? 40 : '100%'));

    if (!str($width)->endsWith('%') && !str($width)->endsWith('px')) $width = $width.'px';
    if (!str($height)->endsWith('%') && !str($height)->endsWith('px')) $height = $height.'px';

    $placeholder = $attributes->get('placeholder');
    $abbr = $placeholder
        ? str($placeholder)->slug()->split('/-/')->take(2)->map(fn($val) => str($val)->upper()->charAt(0))->join('')
        : null;

    $src = $attributes->get('src') ?? $attributes->get('url');
    $file = is_string($src) ? null : $src;
    $url = optional($file)->type === 'youtube' ? data_get($file->data, 'thumbnail') : (optional($file)->url ?? $src);
    $isImage = !$file || $file->isImage || $file->type === 'youtube';
    $icon = $attributes->get('icon') ?? optional($file)->icon;

    $except = ['src', 'alt', 'icon', 'size', 'width', 'height', 'color', 'class', 'style', 'avatar', 'placeholder']
@endphp

<div
    class="relative overflow-hidden bg-gray-100 shadow {{ $isAvatar ? 'rounded-full border' : 'rounded-md' }}"
    style="width: {{ $width }}; height: {{ $height }}"
    {{ $attributes->except($except) }}>
    @if ($abbr && !$url && !$file)
        <div class="flex items-center justify-center w-full h-full font-bold text-gray-100" style="background-color: {{ $color }};">
            {!! $abbr !!}
        </div>
    @elseif ($isImage)
        <img src="{{ $url }}" alt="{{ $alt }}" class="w-full h-full {{ [
            'cover' => 'object-cover',
            'contain' => 'object-contain',
            'fill' => 'object-fill',
            'none' => 'object-none',
            'scale-down' => 'object-scale-down',
        ][$fit] }}">
    @elseif ($icon)
        <div class="w-full h-full flex items-center justify-center text-gray-500">
            <x-icon :name="$file->icon"/>
        </div>
    @endif
</div>