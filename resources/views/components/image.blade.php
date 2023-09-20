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
        if ($color = $attributes->get('color')) {
            return $color === 'random' ? collect([
                'bg-pink-400',
                'bg-rose-400',
                'bg-violet-400',
                'bg-indigo-400',
                'bg-sky-400',
                'bg-teal-400',
                'bg-amber-400',
                'bg-orange-400',
            ])->random() : $color;
        }
        else return 'bg-white';
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
        <div
            x-init="$nextTick(() => $el.style.fontSize = ($el.parentNode.offsetWidth * 0.35)+'px')"
            class="w-full h-full flex items-center justify-center text-white font-bold {{ $getColor() }}"
            style="font-size: 200%;">
            {{ strtoupper(substr($placeholder, 0, 2)) }}
        </div>
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