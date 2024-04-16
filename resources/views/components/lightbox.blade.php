<div
    x-cloak
    x-data="{
        slider: null,
        show: false,
        open (index) {
            $el.classList.add('inset-0')
            $el.classList.remove('-left-[9999]')
            $el.classList.remove('opacity-0')
            this.slider.moveToIdx(index)
        },
        close () {
            $el.classList.add('opacity-0')
            setTimeout(() => {
                $el.classList.remove('inset-0')
                $el.classList.add('-left-[9999]')
            }, 300)
        },
        setupSlider (slider) {
            this.slider = slider
        },
    }"
    x-init="close()"
    x-on:lightbox.window="open($event.detail)"
    class="fixed z-40 bg-black/80 flex items-center justify-center transition-opacity duration-300 opacity-0 -left-[9999]">
    @if ($slot->isNotEmpty())
        <div class="max-w-screen-lg mx-auto p-5">
            <div x-on:click="close()" class="flex justify-end mb-5 text-gray-300 text-4xl cursor-pointer">
                <x-icon name="xmark"/>
            </div>

            <x-slider :autoplay="false" :nav="false">
                {{ $slot }}
            </x-slider>
        </div>
    @endif
</div>
