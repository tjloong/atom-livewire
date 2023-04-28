@props([
    'url' => data_get($image, 'url'),
    'href' => $attributes->get('href'),
    'img' => '
        <img
            src="'.data_get($image, 'url').'"
            width="'.data_get($image, 'width', '1200').'"
            height="'.data_get($image, 'height', '500').'"
            class="'.$attributes->get('class').'"
            alt="'.$attributes->get('alt').'"
            style="
                width: '.data_get($image, 'width').';
                height: '.data_get($image, 'height').';
                object-fit: '.data_get($image, 'fit').';
            "
        >
    ',
])

@if (!$href && !$url && $slot->isNotEmpty())
    <div {{ $attributes->class([
        'swiper-slide w-full h-full',
    ])->only('class') }}>
        {{ $slot }}
    </div>
@elseif ($href)
    <a 
        href="{{ $href }}" 
        target="{{ $attributes->get('target', '_blank') }}"
        class="swiper-slide block relative w-full h-full" 
        {{ $attributes->except(['image', 'href', 'url']) }}
    >
        @if ($url)
            {!! $img !!}
        @endif

        @if ($slot->isNotEmpty())
            <div class="absolute inset-0 z-10 flex p-6">
                {{ $slot }}
            </div>
        @endif
    </a>
@else
    <div
        class="swiper-slide relative w-full h-full"
        {{ $attributes->except(['image', 'href', 'url']) }}
    >
        @if ($url)
            {!! $img !!}
        @endif

        @if ($slot->isNotEmpty())
            <div class="absolute inset-0 z-10 flex p-6">
                {{ $slot }}
            </div>
        @endif
    </div>
@endif