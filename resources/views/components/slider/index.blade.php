<div 
    x-data="{
        config: @js($attributes->get('config')),
        slider: null,
        thumbs: null,
        get slides () {
            return Array.from(this.$refs.slider.querySelectorAll('.swiper-slide'))
        },
        init () {
            this.slider = new Swiper(this.$refs.slider, {
                enabled: this.slides.length > 1, 
                loop: true,
                autoplay: {
                    delay: 3500,
                },
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
            })
        },
        show () {
            [
                this.slider.navigation.prevEl && '#swiper-prev',
                this.slider.navigation.nextEl && '#swiper-next',
                this.slider.pagination.el && '.swiper-pagination',
                this.slider.scrollbar.el && '.swiper-scrollbar',
            ]
                .filter(Boolean)
                .forEach(selector => this.$el.querySelector(selector).classList.remove('hidden'))
        },
    }"
    x-init="init(); show()"
    class="{{ $attributes->get('class') }}"
>
    <div x-ref="slider" class="swiper w-full h-full">
        <div class="swiper-wrapper">{{ $slot }}</div>
        <div class="swiper-pagination hidden"></div>
        <div class="swiper-scrollbar hidden"></div>
        <x-slider.nav prev/>
        <x-slider.nav next/>
    </div>
</div>



{{-- @if (in_array($thumbs['position'], ['top', 'bottom']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div x-data="slider(@js($config), @js($thumbs['config']))" class="flex flex-col gap-2 w-full h-full {{ $attributes->get('class') }}">
        <div class="flex-shrink-0 {{ $thumbs['position'] === 'bottom' ? 'order-last' : '' }}" style="height: 20%">
            <div id="swiper-thumbs" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
            </div>
        </div>

        <div class="flex-grow" style="height: 80%">
            <div id="swiper-slider" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>        
                <div class="swiper-pagination hidden"></div>
                <div class="swiper-scrollbar hidden"></div>
                <x-builder.slider swiper-prev/>
                <x-builder.slider swiper-next/>
            </div>
        </div>
    </div>

@elseif (in_array($thumbs['position'], ['left', 'right']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div 
        x-data="slider(@js($config), @js(array_merge(
            ['direction' => 'vertical'], 
            $thumbs['config'] ?? []
        )))"
        class="w-full h-full flex gap-2 {{ $attributes->get('class') }}"
        @if ($thumbs['height'])
            style="height: {{ $thumbs['height'] }}px;"
        @endif
    >
        <div class="flex-shrink-0 hidden md:block {{ $thumbs['position'] === 'right' ? 'order-last' : '' }}" style="width: 20%">
            <div id="swiper-thumbs" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
            </div>
        </div>

        <div class="flex-grow" style="width: 80%">
            <div id="swiper-slider" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>        
                <div class="swiper-pagination hidden"></div>
                <div class="swiper-scrollbar hidden"></div>
                <x-builder.slider swiper-prev/>
                <x-builder.slider swiper-next/>
            </div>
        </div>
    </div>

@else
    
@endif

@if (!$attributes->has('slide'))
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('slider', (config = null, thumbsConfig = null) => ({
                swiper: null,
                swiperConfig: null,
                swiperThumbs: null,
                swiperThumbsConfig: null,

                init () {
                    ScriptLoader.load([
                        { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.js', type: 'js' },
                        { src: 'https://unpkg.com/swiper@7/swiper-bundle.min.css', type: 'css' },
                    ]).then(() => {
                        this.getConfig()

                        const thumbs = this.$el.querySelector('#swiper-thumbs')
                        if (thumbs) this.swiperThumbs = new Swiper(thumbs, this.swiperThumbsConfig)

                        const slider = this.$el.querySelector('#swiper-slider')
                        
                        this.swiper = new Swiper(slider, {
                            ...this.swiperConfig,
                            thumbs: this.swiperThumbs
                                ? { swiper: this.swiperThumbs }
                                : null,
                        })

                        this.toggleElements()
                    })
                },

                toggleElements () {
                    const prev = this.$el.querySelector('#swiper-prev')
                    const next = this.$el.querySelector('#swiper-next')
                    const pagination = this.$el.querySelector('.swiper-pagination')
                    const scrollbar = this.$el.querySelector('.swiper-scrollbar')

                    if (this.swiperConfig.navigation) {
                        prev.classList.remove('hidden')
                        next.classList.remove('hidden')
                    }

                    if (this.swiperConfig.pagination) pagination.classList.remove('hidden')
                    if (this.swiperConfig.scrollbar) scrollbar.classList.remove('hidden')
                },

                getConfig () {
                    const thumbs = this.$el.querySelector('#swiper-thumbs')
                    const slides = this.$el.querySelectorAll('#swiper-slider .swiper-slide')

                    if (thumbs) {
                        this.swiperThumbsConfig = {
                            enabled: slides.length > 1,
                            spaceBetween: 8,
                            slidesPerView: 5,
                            ...thumbsConfig,
                        }
                    }

                    this.swiperConfig = {
                        enabled: slides.length > 1, 
                        loop: true,
                        autoplay: {
                            delay: 3500,
                        },
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
                        ...config,
                    }
                },
            }))
        })
    </script>

@endif --}}