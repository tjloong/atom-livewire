@if ($attributes->has('slide'))
    <div 
        class="swiper-slide relative {{ $attributes->get('href') ? 'cursor-pointer' : '' }}"
        @if ($attributes->get('href'))
            x-data
            x-on:click="window.open('{{ $attributes->get('href') }}', '_blank')"
        @endif
    >
        @if ($attributes->get('image'))
            <img
                src="{{ $attributes->get('image') }}"
                width="1200"
                height="500"
                class="{{ $attributes->get('class') ?? 'w-full h-full object-cover' }}"
                alt="{{ $attributes->get('alt') }}"
            >
        @endif

        <div 
            class="
                absolute inset-0 z-10 flex p-12
                {{ $overlay ? 'bg-black/30 text-gray-200' : '' }}
                {{ $valign === 'top' ? 'items-start' : '' }}
                {{ $valign === 'center' ? 'items-center' : '' }}
                {{ $valign === 'bottom' ? 'items-end' : '' }}
            "
        >
            <div 
                class="
                    max-w-screen-xl mx-auto flex flex-col gap-4 h-max
                    {{ $align === 'center' ? 'text-center' : '' }}
                    {{ $align === 'right' ? 'text-right' : '' }}
                "
            >
                @isset($title)
                    <div class="text-4xl font-bold">
                        {{ $title }}
                    </div>                        
                @endisset

                <div>{{ $slot }}</div>

                @isset($cta)
                    <div>{{ $cta }}</div>
                @endisset
            </div>
        </div>
    </div>

@elseif ($attributes->has('swiper-prev'))
    <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 pl-4 z-10 flex items-center justify-center">
        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center drop-shadow">
            <x-icon name="chevron-left" size="32px"/>
        </div>
    </div>

@elseif ($attributes->has('swiper-next'))
    <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 pr-4 z-10 flex items-center justify-center">
        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center drop-shadow">
            <x-icon name="chevron-right" size="32px"/>
        </div>
    </div>

@elseif (in_array($thumbsPosition, ['top', 'bottom']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div x-data="slider(@js($attributes->get('config')), @js($attributes->get('thumbs-config')))" class="flex flex-col gap-4 w-full h-full">
        <div class="flex-shrink-0 hidden md:block {{ $thumbsPosition === 'bottom' ? 'order-last' : '' }}" style="height: 20%">
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

@elseif (in_array($thumbsPosition, ['left', 'right']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div x-data="slider(
        @js($attributes->get('config')), 
        @js(array_merge(['direction' => 'vertical'], $attributes->get('thumbs-config') ?? []))
    )" class="w-full h-full flex gap-4">
        <div class="flex-shrink-0 hidden md:block {{ $thumbsPosition === 'right' ? 'order-last' : '' }}" style="width: 20%">
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
    <div x-data="slider(@js($attributes->get('config')))" class="w-full h-full">
        <div id="swiper-slider" class="swiper w-full h-full">
            <div class="swiper-wrapper">{{ $slot }}</div>
            <div class="swiper-pagination hidden"></div>
            <div class="swiper-scrollbar hidden"></div>
            <x-builder.slider swiper-prev/>
            <x-builder.slider swiper-next/>
        </div>
    </div>
    
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
                            spaceBetween: 16,
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

@endif