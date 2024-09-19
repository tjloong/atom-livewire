<div
    wire:ignore
    x-cloak
    x-data="{
        show: false,
        gallery: [],
        pointer: null,

        open ({ gallery = [], slide }) {
            // setup gallery
            this.gallery = gallery.map(item => {
                if (typeof item === 'string') return { endpoint: item, is_image: true }
                else return item
            })

            // setup pointer
            if (typeof slide === 'number') this.pointer = slide
            else if (typeof slide === 'string') this.pointer = this.gallery.findIndex(item => (item.endpoint === slide))
            else if (slide.id) this.pointer = this.gallery.findIndex(item => (item.id === slide.id))
            else if (slide.endpoint) this.pointer = this.gallery.findIndex(item => (item.endpoint === slide.endpoint))
            else if (slide.url) this.pointer = this.gallery.findIndex(item => (item.url === slide.url))
            if (!this.pointer) this.pointer = 0

            this.show = true
        },

        close () {
            this.show = false
        },

        prev () {
            let prev = ((this.pointer + this.gallery.length) - 1) % this.gallery.length
            this.pointer = null
            setTimeout(() => this.pointer = prev, 150)
        },

        next () {
            let next = (this.pointer + 1) % this.gallery.length
            this.pointer = null
            setTimeout(() => this.pointer = next, 150)
        },
    }"
    x-on:lightbox.window="open($event.detail)"
    x-wire-on:lightbox="open($args)">
    <template x-teleport="body">
        <div
            x-show="show"
            x-transition.opacity.duration.100
            x-on:click="close()"
            class="fixed inset-0 bg-black/80 flex items-center justify-center overflow-auto" style="z-index: 999">
            <div
                x-on:click.stop="prev()"
                class="absolute top-0 bottom-0 left-10 w-14 flex items-center justify-center text-gray-100 cursor-pointer">
                <x-icon left size="32"/>
            </div>

            <div
                x-on:click.stop="next()"
                class="absolute top-0 bottom-0 right-10 w-14 flex items-center justify-center text-gray-100 cursor-pointer">
                <x-icon right size="32"/>
            </div>

            <div
                x-on:click="close()"
                class="absolute top-5 right-10 w-10 h-10 cursor-pointer flex items-center justify-center text-gray-100">
                <x-icon close size="28"/>
            </div>

            <template x-for="(item, i) in gallery" hidden>
                <div
                    x-show="i === pointer"
                    x-transition.opacity.100
                    x-on:click.stop
                    class="flex items-center justify-center">
                    <template x-if="item.is_image" hidden>
                        <img x-bind:src="item.endpoint" style="max-width: 75vw;">
                    </template>

                    <template x-if="!item.is_image" hidden>
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="w-40 h-40 bg-gray-200 rounded-lg flex items-center justify-center">
                                <x-icon file size="40"/>
                            </div>

                            <div x-show="item.name" x-text="item.name" class="text-gray-100 font-medium"></div>

                            <x-button action="download" x-on:click="window.open(item.endpoint, '_blank')"/>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
