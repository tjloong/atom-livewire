@props([
    'icon' => $attributes->get('icon'),
    'placeholder' => $attributes->get('placeholder'),
    'isAvatar' => $attributes->get('avatar', false),
    'getUrl' => function() use ($attributes) {
        return is_string($attributes->get('src'))
            ? $attributes->get('src')
            : null;
    },
    'getFile' => function($attr = null) use ($attributes) {
        $src = $attributes->get('src');

        if ($file = is_numeric($src) 
            ? model('file')->find($src)
            : (optional($src)->id ? $src : null)
        ) {
            return $attr ? data_get($file, $attr) : $file;
        }
    },
    'getColor' => function() use ($attributes) {
        $color = $attributes->get('color');
        $colors = [
            'pink' => '#ff7675',
            'red' => '#d63031',
            'orange' => '#e17055',
            'yellow' => '#fdcb6e',
            'green' => '#00b894',
            'blue' => '#0984e3',
            'purple' => '#6c5ce7',
            'gray' => '#636e72',
        ];

        if ($color === 'random' || (!$color && $attributes->get('avatar', false))) {
            return collect($colors)->values()->random();
        }
        else if ($color) {
            return $colors[$color];
        }
        else return '#ffffff';
    },
    'getSize' => function($name) use ($attributes) {
        $width = $attributes->get('width');
        $height = $attributes->get('height');
        $size = explode('x', $attributes->get('size'));

        $width = $width ?? head($size) ?? null;
        $height = $height ?? last($size) ?? null;

        return empty($$name)
            ? ($attributes->get('avatar') ? 40 : '100%')
            : $$name;
    },
])

<div @if ($getFile('is_file')) onClick="window.open(@js($getFile('url')), '_blank')" @endif
    {{ $attributes
        ->class([
            'relative overflow-hidden',
            'rounded-full border' => $isAvatar,
            'rounded-lg' => !$isAvatar,
            'cursor-pointer' => $getFile('is_file'),
            $attributes->get('class', ''),
        ])
        ->merge(['style' => collect([
            'width: '.(is_numeric($getSize('width')) ? ($getSize('width').'px') : $getSize('width')),
            'height: '.(is_numeric($getSize('height')) ? ($getSize('height').'px') : $getSize('height')),
        ])->join('; ')])
        ->only(['class', 'style']) }}
    {{ $attributes->except(['src', 'alt', 'icon', 'size', 'width', 'height', 'color', 'class', 'avatar', 'placeholder']) }}>
    @if ($placeholder && !$getUrl() && !$getFile())
        <img src="https://placehold.co/300x300/{{ str($getColor())->replace('#', '')->toString() }}/ffffff?text={{ strtoupper(substr($placeholder, 0, 2)) }}" class="w-full h-full"/>
    @elseif ($url = $getUrl())
        <img src="{{ $url }}" class="w-full h-full object-cover"
            width="{{ is_numeric($getSize('width')) ? $getSize('width') : null }}"
            height="{{ is_numeric($getSize('height')) ? $getSize('height') : null }}"
            alt="{{ $attributes->get('alt') }}">
    @elseif ($file = $getFile())
        @if ($file->is_image)
            <div class="w-full h-full bg-white">
                <img src="{{ $file->url }}" class="w-full h-full object-cover"
                    width="{{ is_numeric($getSize('width')) ? $getSize('width') : null }}"
                    height="{{ is_numeric($getSize('height')) ? $getSize('height') : null }}"
                    alt="{{ $attributes->get('alt') }}">
            </div>
        @else
            <div x-data x-cloak x-init="$nextTick(() => $el.style.fontSize = ($el.parentNode.offsetWidth * 0.35)+'px')"
                class="w-full h-full flex items-center justify-center text-gray-500">
                <x-icon :name="$file->icon"/>
            </div>
        @endif
    @endif
</div>