@props(['uid' => $attributes->get('uid', 'lightbox')])

<div
    x-cloak
    x-data="{
        show: false,
        slider: null,
        config: @js($attributes->get('config')),
        get slides () {
            return Array.from(this.$refs.slider.querySelectorAll('.swiper-slide'))
        },
        open (index) {
            this.show = true

            if (!this.slider) {
                this.setConfigs(index)
                this.slider = new Swiper(this.$refs.slider, this.config)
                this.$nextTick(() => {
                    [
                        this.slider.navigation.prevEl && '#swiper-prev',
                        this.slider.navigation.nextEl && '#swiper-next',
                        this.slider.pagination.el && '.swiper-pagination',
                        this.slider.scrollbar.el && '.swiper-scrollbar',
                    ].filter(Boolean).forEach(selector => this.$el.querySelector(selector).classList.remove('hidden'))
                })
            }
            else {
                this.$nextTick(() => this.slider.slideTo(index + 1))
            }
        },
        close () {
            this.show = false
            this.$dispatch('{{ $uid }}-close')
        },
        setConfigs (index) {
            this.config = {
                enabled: this.slides.length > 1, 
                initialSlide: Number.isFinite(index) ? index : 0,
                loop: true,
                navigation: {
                    nextEl: '#swiper-next',
                    prevEl: '#swiper-prev',
                    disabledClass: 'hidden',
                    hiddenClass: 'hidden',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    hiddenClass: 'hidden',
                    bulletClass: 'inline-block mx-1 rounded-full bg-gray-100 w-2 h-2 drop-shadow',
                    bulletActiveClass: 'bg-theme px-4',
                },
                ...this.config,
            }
        },
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $uid }}-open.window="open($event.detail)"
    x-on:click="close()"
    class="fixed inset-0 bg-black/80 z-40 py-20 px-6"
>
    <a class="absolute top-4 right-8 w-10 h-10 bg-white shadow rounded-full flex">
        <x-icon name="xmark" class="m-auto" size="16px"/>
    </a>

    <div x-ref="slider" x-on:click.stop class="swiper w-full h-full">
        <div class="swiper-wrapper">{{ $slot }}</div>
        <div class="swiper-pagination hidden"></div>
        <div class="swiper-scrollbar hidden"></div>
        <x-slider.nav prev/>
        <x-slider.nav next/>
    </div>
</div>
