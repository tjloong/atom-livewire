@php
    $nav = $attributes->get('nav', true);
    $arrows = $attributes->get('arrows', true);
    $autoplay = $attributes->get('autoplay', 2500);
@endphp

<div {{ $attributes }}>
    <div
        x-cloak
        x-data="{
            slider: null,
            navPlugin (slider) {
                if (this.$refs.nav) {
                    slider.on('created', () => {
                        const elm = $refs.nav.querySelector('*')
                        const classNames = elm.getAttribute('class')

                        $refs.nav.innerHTML = ''

                        for (let i = 0; i < slider.slides.length; i++) {
                            const div = document.createElement('div')
                            classNames.split(' ').forEach(name => div.classList.add(name))

                            if (i === slider.track.details.rel) div.classList.add('w-5')

                            div.addEventListener('click', (e) => {
                                slider.moveToIdx(i)
                                Array.from($refs.nav.querySelectorAll('*')).forEach(elm => elm.classList.remove('w-5'))
                                e.target.classList.add('w-5')
                            })

                            $refs.nav.append(div)
                        }
                    })
                }
            },
            autoplayPlugin (slider) {
                let timer
                let play = () => timer = setInterval(() => slider.next(), {{ $autoplay }})
                let stop = () => clearInterval(timer)

                slider.on('created', () => {
                    slider.container.addEventListener('mouseover', () => stop())
                    slider.container.addEventListener('mouseout', () => play())
                    play()
                })
                
                slider.on('dragStarted', () => stop())
            },
        }"
        x-init="$nextTick(() => {
            Array.from($refs.slider.querySelectorAll(':scope > *'))
                .forEach(elm => elm.classList.add('keen-slider__slide'))

            slider = new KeenSlider(
                $refs.slider, 
                { loop: true },
                [
                    (slider) => navPlugin(slider),
                    (slider) => autoplayPlugin(slider),
                    (slider) => {
                        try { setupSlider(slider) }
                        catch (err) {}
                    },
                ],
            )
        })"
        class="relative">
        <div x-ref="slider" class="keen-slider">
            {{ $slot }}
        </div>
    
        @if ($arrows)
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
        @endif

        @if ($nav)
            <div x-ref="nav" class="keen-slider__nav absolute bottom-0 left-0 right-0 flex items-center justify-center gap-2 py-3">
                @isset($dot) {{ $dot }}
                @else
                    <div class="w-2 h-2 bg-theme rounded-full cursor-pointer"></div>
                @endisset
            </div>
        @endif
    </div>
</div>
