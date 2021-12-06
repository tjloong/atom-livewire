<div {{ $attributes->merge(['class' => 'swiper-slide']) }}>
    @if ($attributes->has('src'))
        <img src="{{ $attributes->get('src') }}" alt="{{ $attributes->get('alt') }}" class="w-full h-full object-cover">
    @else
        {{ $slot }}
    @endif
</div>