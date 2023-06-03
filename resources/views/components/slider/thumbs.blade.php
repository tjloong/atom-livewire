@props([
    'id' => $attributes->get('id', 'thumbs'),
    'config' => $attributes->get('config'),
])

<div
    x-cloak
    x-data="{
        get thumbs () {
            return Array.from(this.$refs.thumbs.querySelectorAll('.swiper-slide'))
        },
        get config () {
            return {
                enabled: this.thumbs.length > 1,
                loop: true,
                spaceBetween: 8,
                slidesPerView: 5,
                freeMode: true,
                watchSlidesProgress: true,
                ...@js($config),
            }
        },
        init () {
            const swiper = new Swiper(this.$refs.thumbs, this.config)
            
            this.$nextTick(() => this.$dispatch('thumbs-started', @js($id)))
        },
    }"
    wire:ignore
    {{ $attributes->except('id') }}
>
    <div x-ref="thumbs" 
        class="swiper swiper-thumbs w-full h-full"
        id="{{ $id }}" 
    >
        <div class="swiper-wrapper">{{ $slot }}</div>
    </div>
</div>

<style>
    .swiper-thumbs .swiper-slide { opacity: 0.4; }
    .swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
</style>
