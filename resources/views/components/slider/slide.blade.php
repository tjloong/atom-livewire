@props([
    'url' => data_get($image, 'url'),
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

@if ($href = $attributes->get('href'))
    <a href="{{ $href }}" class="swiper-slide block relative w-full h-full" target="_blank">
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
    <div class="swiper-slide relative w-full h-full">
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

