@php
$id = $attributes->get('id') ?? $this->id;
$render = $slot->isNotEmpty() && isset($this->isLayerOpened) && $this->isLayerOpened;
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        nav: null,
        show: false,

        open () {
            this.show = true
            $layering.zindex()
            document.body.style.overflow = 'hidden'
            this.$el.style.top = document.querySelector('.app-layout-header').offsetHeight+'px'
        },

        close () {
            this.show = false
            if ($layering.isEmpty()) document.body.style.overflow = 'auto'
        },
    }"
    x-show="show"
    x-transition.opacity.duration.300
    x-on:open.stop="open()"
    x-on:close.stop="close()"
    x-on:open-layer.window="id === $event.detail && open()"
    x-on:close-layer.window="id === $event.detail && close()"
    x-on:app-layout-nav-updated.window="nav = $event.detail"
    x-bind:class="{
        'left-0 lg:left-0': nav === 'hidden',
        'left-0 lg:left-60': !nav || nav === 'lg',
        'active': show,
    }"
    class="app-layout-layer fixed z-40 bottom-0 right-0 overflow-auto bg-gray-50 transition-all duration-200"
    {{ $attributes->wire('close') }}>
    <div {{ $attributes->class([
        'mx-auto p-5',
        $attributes->get('class', 'max-w-screen-2xl'),
    ])->only('class') }}>
        @isset ($top)
            {{ $top }}
        @else
            <div class="flex items-center gap-4 flex-wrap mb-5">
                @if (($back ?? null)?->isNotEmpty())
                    {{ $back }}
                @else
                    <div class="shrink-0" x-on:click="$dispatch('close')">
                        <x-inline 
                            label="{{ ($back ?? null)?->attributes?->get('label') ?? 'app.label.back' }}" 
                            icon="back" 
                            class="bg-gray-200 rounded-full cursor-pointer text-sm py-1 px-3 font-medium hover:ring-1 hover:ring-offset-2 hover:ring-gray-200">
                        </x-inline>
                    </div>
                @endisset

                @isset ($buttons)
                    <div class="grow flex items-center justify-end flex-wrap gap-2">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>
        @endisset

        {{ $slot }}
    </div>
</div>