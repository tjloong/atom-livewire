@props([
    'config' => $attributes->get('config'),
    'thumbs' => $attributes->get('thumbs'),
])

<div 
    
    x-cloak
    x-data="{
        thumbs: @js($thumbs),
        swiper: null,
        get slides () {
            return Array.from(this.$refs.slider.querySelectorAll('.swiper-slide'))
        },
        get config () {
            return {
                enabled: this.slides.length > 1, 
                loop: true,
                autoplay: {
                    delay: 3500,
                },
                navigation: this.slides.length > 1 ? {
                    nextEl: '#swiper-next',
                    prevEl: '#swiper-prev',
                    disabledClass: 'hidden',
                    hiddenClass: 'hidden',
                } : { enabled: false },
                pagination: this.slides.length > 1 ? {
                    el: '.swiper-pagination',
                    clickable: true,
                    hiddenClass: 'hidden',
                    bulletClass: 'inline-block mx-1 rounded-full bg-gray-100 w-2 h-2 drop-shadow',
                    bulletActiveClass: 'bg-theme px-4',
                } : { enabled: false },
                on: {
                    init: (swiper) => this.$dispatch('slider-init', swiper),
                    click: (swiper) => this.$dispatch('slider-slide-click', swiper),
                    slideChangeTransitionEnd: (swiper) => this.$dispatch('slider-slide-change', swiper),
                },
                ...@js($config),
            }
        },
        start (thumbsId) {
            let config = { ...this.config }

            if (this.thumbs) {
                const id = this.thumbs === true ? 'thumbs' : this.thumbs
                const thumbs = id === thumbsId ? document.querySelector(`#${id}`) : null

                config.thumbs = { swiper: thumbs.swiper }
            }
            
            this.swiper = new Swiper(this.$refs.slider, config)

            this.$nextTick(() => {
                [
                    this.swiper.navigation.prevEl && '#swiper-prev',
                    this.swiper.navigation.nextEl && '#swiper-next',
                    this.swiper.pagination.el && '.swiper-pagination',
                    this.swiper.scrollbar.el && '.swiper-scrollbar',
                ].filter(Boolean).forEach(selector => this.$el.querySelector(selector).classList.remove('hidden'))
            })
        },
    }"
    @if ($thumbs) x-on:thumbs-started.window="start($event.detail)"
    @else x-init="start()"
    @endif
    wire:ignore
    {{ $attributes }}
>
    <div x-ref="slider" class="swiper w-full h-full">
        <div class="swiper-wrapper">{{ $slot }}</div>
        <div class="swiper-pagination hidden"></div>
        <div class="swiper-scrollbar hidden"></div>
        <x-slider.nav prev/>
        <x-slider.nav next/>
    </div>
</div>
