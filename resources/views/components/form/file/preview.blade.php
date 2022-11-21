@props(['uid' => $attributes->get('uid', 'file-preview')])

<div
    x-data="{
        url: null,
    }"
    x-show="!empty(url)"
    x-transition
    x-on:{{ $uid }}-open.window="url = $event.detail"
    x-on:{{ $uid }}-close.window="url = null"
    class="fixed inset-0 z-40 overflow-auto flex px-6 py-10"
>
    <div x-on:click="url = null" class="fixed inset-0 bg-black/50"></div>
    <div class="relative max-w-screen-lg bg-white rounded-lg shadow m-auto">
        <template x-if="url && url.includes('youtube.com')">
            <iframe 
                x-bind:src="file.url"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
            ></iframe>
        </template>

        <template x-if="url && !url.includes('youtube.com')">
            <img 
                class="w-full h-full object-contain"
                x-bind:src="url" class="w-full h-full object-contain"
            >
        </template>
    </div>
</div>