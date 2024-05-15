@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$render = $slot->isNotEmpty() && isset($this->isLayerOpened) && $this->isLayerOpened;
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        nav: null,
        show: @entangle('isLayerOpened'),

        open () {
            this.show = true
            this.$dispatch('open')
            $layering.zindex()
            $layering.lockScroll()
            this.$el.style.top = document.querySelector('.app-layout-header').offsetHeight+'px'
        },

        close () {
            this.show = false
            this.$dispatch('close')
            if ($layering.isEmpty()) $layering.unlockScroll()
        },
    }"
    x-show="show"
    x-transition.opacity.duration.300
    x-on:open-layer.window="id === $event.detail && open()"
    x-on:close-layer.window="id === $event.detail && close()"
    x-on:app-layout-nav-updated.window="nav = $event.detail"
    x-bind:class="{
        'left-0 lg:left-0': nav === 'hidden',
        'left-0 lg:left-60': !nav || nav === 'lg',
        'active': show,
    }"
    data-layer-id="{{ $id }}"
    class="app-layout-layer fixed z-40 bottom-0 right-0 overflow-auto bg-gray-50 transition-all duration-200"
    {{ $attributes->except(['class', 'id']) }}>
    <div {{ $attributes->class([
        $attributes->get('class', 'max-w-screen-2xl mx-auto p-5 w-full h-full'),
    ])->only('class') }}>
        @isset ($top)
            {{ $top }}
        @else
            <div class="flex items-center gap-4 flex-wrap mb-5">
                @if (($back ?? null)?->isNotEmpty())
                    {{ $back }}
                @else
                    <div class="shrink-0" {{ ($back ?? null)?->attributes?->merge(['x-on:click' => 'close()']) }}>
                        <x-inline 
                            label="{{ ($back ?? null)?->attributes?->get('label') ?? 'app.label.back' }}" 
                            icon="back" 
                            class="bg-gray-200 rounded-full cursor-pointer text-sm py-1 px-3 font-medium hover:ring-1 hover:ring-offset-2 hover:ring-gray-200">
                        </x-inline>
                    </div>
                @endisset

                @isset ($buttons)
                    <div class="grow flex items-center md:justify-end flex-wrap gap-2">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>
        @endisset

        @if (isset($this->isLayerOpened) && $this->isLayerOpened)
            {{ $slot }}
        @endif
    </div>
</div>