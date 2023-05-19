@props(['id' => component_id($attributes, 'file-preview')])

<div
    x-data="{
        url: null,
        show: false,
        open (url) {
            this.url = url
            this.show = true
        },
        close () {
            this.url = null
            this.show = false
        },
    }"
    x-show="show"
    x-transition
    x-on:open="open($event.detail)"
    x-on:click.stop="close"
    x-on:keyup.escape.window="close"
    x-bind:class="show && 'fixed inset-0 z-40 overflow-auto flex px-6 py-10'"
    id="{{ $id }}"
>
    <div class="fixed inset-0 bg-black/80"></div>
    <div class="relative max-w-screen-lg m-auto">
        <template x-if="url && url.includes('youtube.com')">
            <iframe 
                x-bind:src="file.url"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
            ></iframe>
        </template>

        <template x-if="url && !url.includes('youtube.com')">
            <div class="w-full h-[500px]">
                <img 
                    class="w-full h-full object-contain"
                    x-bind:src="url" class="w-full h-full object-contain"
                >
            </div>
        </template>
    </div>
</div>