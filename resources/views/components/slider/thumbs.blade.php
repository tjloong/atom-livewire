@props([
    'config' => $attributes->get('config'),
])

<style>
    .swiper-thumbs .swiper-slide { opacity: 0.4; }
    .swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
</style>

<div x-data="{
    get thumbs () {
        return Array.from(this.$refs.thumbs.querySelectorAll('.swiper-slide'))
    },
    get config () {
        return {
            enabled: this.thumbs.length > 1,
            spaceBetween: 8,
            slidesPerView: 5,
            loop: true,
            centeredSlides: true,
            ...@js($config),
        }
    },
    init () {
        new Swiper(this.$refs.thumbs, this.config)
    },
}" {{ $attributes }}>
    <div x-ref="thumbs" class="swiper swiper-thumbs w-full h-full">
        <div class="swiper-wrapper">{{ $slot }}</div>
    </div>
</div>
