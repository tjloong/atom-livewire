<div class="swiper-slide relative w-full h-full">
    <img src="{{ $attributes->get('src') ?? $attributes->get('url') }}" 
        {{ $attributes->class(['w-full h-full object-contain']) }}
    >
</div>
