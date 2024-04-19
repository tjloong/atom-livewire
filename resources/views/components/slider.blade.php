<div {{ $attributes }}>
    <div x-cloak {{ $attributes->merge(['x-data' => 'slider']) }}>
        <div class="relative">
            @isset($slides)
                <div x-ref="slider" {{ $slides->attributes->merge(['class' => 'keen-slider']) }}>
                    {{ $slides }}
                </div>
            @else
                <div x-ref="slider" class="keen-slider">
                    {{ $slot }}
                </div>
            @endisset

            <template x-if="config.arrows">
                <div x-ref="arrows" class="keen-slider__arrows">
                    <button type="button" x-on:click="slider.prev()" class="absolute top-0 left-2 bottom-0 flex items-center justify-center">
                        @isset($prev) {{ $prev }}
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100/50 text-gray-600 flex items-center justify-center">
                                <x-icon name="arrow-left"/>
                            </div>
                        @endisset
                    </button>

                    <button type="button" x-on:click="slider.next()" class="absolute top-0 right-2 bottom-0 flex items-center justify-center">
                        @isset($next) {{ $next }}
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100/50 text-gray-600 flex items-center justify-center">
                                <x-icon name="arrow-right"/>
                            </div>
                        @endisset
                    </button>
                </div>
            </template>

            <template x-if="config.nav">
                <div x-ref="nav" class="keen-slider__nav absolute bottom-0 left-0 right-0 flex items-center justify-center gap-2 py-3">
                    @isset($dot) {{ $dot }}
                    @else <div class="w-2 h-2 bg-theme rounded-full cursor-pointer"></div>
                    @endisset
                </div>
            </template>
        </div>

        @isset($thumbnails)
            <div class="mt-2 relative">
                <div x-ref="thumbnails" {{ $thumbnails->attributes->merge(['class' => 'keen-slider']) }}>
                    {{ $thumbnails }}
                </div>

                <button type="button" x-on:click="slider.prev()" class="absolute bg-white/80 top-0 left-0 bottom-0 w-5 flex items-center justify-center">
                    <x-icon name="chevron-left" class="text-gray-500"/>
                </button>

                <button type="button" x-on:click="slider.next()" class="absolute bg-white/80 top-0 right-0 bottom-0 w-5 flex items-center justify-center">
                    <x-icon name="chevron-right" class="text-gray-500"/>
                </button>
            </div>
        @endisset
    </div>
</div>

@once
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.css">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('slider', (config = {}, plugins = []) => ({
                config: {
                    nav: true,
                    arrows: true,
                    autoplay: 2500,
                    adaptiveHeight: false,
                    ...config,
                },
                plugins,
                slider: null,
                thumbnails: null,

                init () {
                    this.$nextTick(() => {
                        this.startSlider()
                        this.startThumbnails()
                    })
                },

                startSlider () {
                    if (!this.$refs.slider) return

                    this.identifySlides(this.$refs.slider)
                    this.slider = new KeenSlider(this.$refs.slider, this.config, this.getPlugins())
                },

                startThumbnails () {
                    if (!this.$refs.thumbnails) return

                    this.identifySlides(this.$refs.thumbnails)
                    this.thumbnails = new KeenSlider(this.$refs.thumbnails, {
                        slides: {
                            perView: 6,
                            spacing: 4,
                        },
                        breakpoints: {
                            '(min-width: 1000px)': {
                                slides: {
                                    perView: 8,
                                    spacing: 4,
                                },
                            },
                        },
                    }, [(thumbnails) => this.thumbnailsPlugin(thumbnails)])
                },

                identifySlides (container) {
                    Array.from(container.querySelectorAll(':scope > *'))
                        .forEach(elm => elm.classList.add('keen-slider__slide'))
                },

                navPlugin (slider) {
                    const goToSlide = (i) => slider.moveToIdx(i)

                    const activateDot = () => {
                        const dots = Array.from(this.$refs.nav.querySelectorAll('*'))
                        const dot = dots[slider.track.details.rel]
                        dots.forEach(elm => elm.classList.remove('w-5'))
                        dot.classList.add('w-5')
                    }

                    const createDots = () => {
                        const elm = this.$refs.nav.querySelector('*')
                        const classNames = elm.getAttribute('class')
                        this.$refs.nav.innerHTML = ''

                        for (let i = 0; i < slider.track.details.slidesLength; i++) {
                            const div = document.createElement('div')
                            classNames.split(' ').forEach(name => div.classList.add(name))
                            div.addEventListener('click', () => goToSlide(i))
                            this.$refs.nav.append(div)
                        }

                        activateDot()
                    }

                    if (this.$refs.nav) {
                        slider.on('created', () => createDots())
                        slider.on('slideChanged', () => activateDot())
                    }
                },

                autoplayPlugin (slider) {
                    let timer
                    let play = () => timer = setInterval(() => slider.next(), this.config.autoplay)
                    let stop = () => clearInterval(timer)

                    slider.on('created', () => {
                        this.$el.addEventListener('mouseover', () => stop())
                        this.$el.addEventListener('mouseout', () => play())
                        play()
                    })
                    
                    slider.on('dragStarted', () => stop())
                },

                adaptiveHeightPlugin (slider) {
                    let updateHeight = () => {
                        slider.container.style.height = slider.slides[slider.track.details.rel].offsetHeight + 'px'
                    }

                    slider.on('created', updateHeight)
                    slider.on('slideChanged', updateHeight)
                },

                helpersPlugin (slider) {
                    slider.getSlide = (index) => (slider.slides[index || slider.track.details.rel])
                    slider.getSlideName = (index) => (slider.getSlide(index)?.getAttribute('data-slide-name'))
                    slider.slides.forEach(slide => slide.addEventListener('click', () => slider.emit('slideClicked')))
                },

                thumbnailsPlugin (thumbnails) {
                    const activateSlide = (i) => {
                        thumbnails.slides.forEach(slide => slide.classList.add('opacity-40'))
                        thumbnails.slides[i].classList.remove('opacity-40')
                    }

                    thumbnails.on('created', () => {
                        activateSlide(this.slider.track.details.rel)
                        thumbnails.slides.forEach((slide, index) => {
                            slide.addEventListener('click', () => this.slider.moveToIdx(index))
                        })
                    })

                    this.slider.on('animationStarted', () => {
                        const nextIndex = this.slider.animator.targetIdx || 0
                        activateSlide(this.slider.track.absToRel(nextIndex))
                        thumbnails.moveToIdx(Math.min(thumbnails.track.details.maxIdx, nextIndex))
                    })
                },

                getPlugins () {
                    let plugins = [(slider) => this.helpersPlugin(slider)]

                    if (this.config.nav) plugins.push((slider) => this.navPlugin(slider))
                    if (this.config.autoplay) plugins.push((slider) => this.autoplayPlugin(slider))
                    if (this.config.adaptiveHeight) plugins.push((slider) => this.adaptiveHeightPlugin(slider))

                    return [
                        ...plugins,
                        ...this.plugins,
                    ]
                },
            }))
        })
    </script>
@endpush
@endonce
