@props([
    'config' => $attributes->get('config'),
])

<div 
    x-cloak
    x-data="{
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
                    slideChangeTransitionEnd: (swiper) => this.$dispatch('slider-slide-change', swiper),
                },
                ...@js($config),
            }
        },
        init () {
            const swiper = new Swiper(this.$refs.slider, this.config)

            this.$nextTick(() => {
                [
                    swiper.navigation.prevEl && '#swiper-prev',
                    swiper.navigation.nextEl && '#swiper-next',
                    swiper.pagination.el && '.swiper-pagination',
                    swiper.scrollbar.el && '.swiper-scrollbar',
                ].filter(Boolean).forEach(selector => this.$el.querySelector(selector).classList.remove('hidden'))
            })
        },
    }"
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
