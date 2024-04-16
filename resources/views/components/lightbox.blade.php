<div
    x-cloak
    x-data="{
        show: false,
        slider: null,
        dimension: 'w-[350px] h-[500px] md:w-[700px] lg:w-[1000px] lg:h-[800px]',
        images: {{ Js::from($attributes->get('images', [])) }},
        open (index = 0) {
            this.show = true
            this.startSlider(index)
        },
        close () {
            this.show = false

            if (this.slider) {
                this.slider = false
            }
        },
        startSlider (index) {
            this.$refs.slider.innerHTML = ''

            this.images.forEach(url => {
                let img = document.createElement('img')
                img.classList.add('keen-slider__slide')
                img.classList.add('object-contain')
                this.dimension.split(' ').forEach(name => img.classList.add(name))
                img.src = url

                this.$refs.slider.appendChild(img)
            })

            this.$nextTick(() => {
                this.slider = new KeenSlider(this.$refs.slider, { 
                    loop: true,
                    initial: index,
                })
            })
        },
    }"
    x-show="show"
    x-transition.opacity.duration.300ms
    x-on:lightbox.window="open($event.detail)"
    x-on:click="close()"
    class="fixed inset-0 z-40 bg-black/80">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div x-bind:class="dimension">
            <div x-on:click="close()" class="flex justify-end mb-5 text-gray-300 text-4xl cursor-pointer">
                <x-icon name="xmark"/>
            </div>

            <div class="relative">
                <div x-ref="slider" x-on:click.stop class="keen-slider"></div>

                <div
                    x-on:click.stop="slider.prev()"
                    class="absolute top-0 left-0 bottom-0 w-10 md:w-20 bg-black/10 text-white flex items-center justify-center cursor-pointer">
                    <x-icon name="arrow-left" class="text-3xl"/>
                </div>

                <div
                    x-on:click.stop="slider.next()"
                    class="absolute top-0 right-0 bottom-0 w-10 md:w-20 bg-black/10 text-white flex items-center justify-center cursor-pointer">
                    <x-icon name="arrow-right" class="text-3xl"/>
                </div>
            </div>
        </div>
    </div>
</div>
