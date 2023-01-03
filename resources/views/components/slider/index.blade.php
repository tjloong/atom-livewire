<style>
    .swiper-thumbs .swiper-slide { opacity: 0.4; }
    .swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
</style>

<div 
    x-data="{
        slider: {
            instance: null,
            config: @js($attributes->get('config')),
        },
        thumbs: {
            instance: null,
            config: @js($attributes->get('thumbs')),
        },
        get slides () {
            return Array.from(this.$refs.slider.querySelectorAll('.swiper-slide'))
        },
        init () {
            this.setConfigs()

            if (this.thumbs.config) {
                this.thumbs.instance = new Swiper(this.$refs.thumbs, this.thumbs.config)
                this.slider.instance = new Swiper(this.$refs.slider, {
                    ...this.slider.config,
                    thumbs: { swiper: this.thumbs.instance },
                })
            }
            else {
                this.slider.instance = new Swiper(this.$refs.slider, this.slider.config)
            }

            this.$nextTick(() => {
                [
                    this.slider.instance.navigation.prevEl && '#swiper-prev',
                    this.slider.instance.navigation.nextEl && '#swiper-next',
                    this.slider.instance.pagination.el && '.swiper-pagination',
                    this.slider.instance.scrollbar.el && '.swiper-scrollbar',
                ].filter(Boolean).forEach(selector => this.$el.querySelector(selector).classList.remove('hidden'))
            })
        },
        setConfigs () {
            this.slider.config = {
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
                ...this.slider.config,
            }

            if (this.thumbs.config) {
                this.thumbs.config = {
                    enabled: this.slides.length > 1,
                    spaceBetween: 8,
                    slidesPerView: 5,
                    loop: true,
                    centeredSlides: true,
                    ...this.thumbs.config,
                }
            }
        },
    }"
    class="{{ $attributes->get('class') }}"
>
    <div x-ref="slider" class="swiper w-full h-full">
        <div class="swiper-wrapper">{{ $slot }}</div>
        <div class="swiper-pagination hidden"></div>
        <div class="swiper-scrollbar hidden"></div>
        <x-slider.nav prev/>
        <x-slider.nav next/>
    </div>

    @if ($attributes->get('thumbs'))
        <div x-ref="thumbs" class="swiper swiper-thumbs w-full h-full">
            <div class="swiper-wrapper">{{ $slot }}</div>
        </div>
    @endif
</div>
