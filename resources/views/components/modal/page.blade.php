@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$show = $attributes->get('show', false);
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        nav: null,
        show: @js($show),

        open () {
            if (this.show) return
            this.show = true
            this.$dispatch('open')
            $modal.zindex()
            $modal.lockScroll()
            this.$el.style.top = document.querySelector('.app-layout-header').offsetHeight+'px'
        },

        close () {
            if (!this.show) return
            this.show = false
            this.$dispatch('close')
            if ($modal.isEmpty()) $modal.unlockScroll()
        },
    }"
    x-show="show"
    x-transition.opacity.duration.300
    x-on:open-modal.window="id === $event.detail && open()"
    x-on:close-modal.window="id === $event.detail && close()"
    x-on:app-layout-nav-changed.window="nav = $event.detail"
    x-bind:class="{
        'left-0 lg:left-0': nav === 'hidden',
        'left-0 lg:left-60': !nav || nav === 'lg',
        'active': show,
    }"
    data-modal-id="{{ $id }}"
    class="modal-page fixed z-40 bottom-0 right-0 overflow-auto bg-gray-50 transition-all duration-200"
    {{ $attributes->except(['class', 'id']) }}>
    <div {{ $attributes->class([
        $attributes->get('class', 'max-w-screen-2xl mx-auto p-5 w-full min-h-full'),
    ])->only('class') }}>
        @isset ($top)
            {{ $top }}
        @else
            <div class="flex items-center gap-4 flex-wrap mb-5">
                @if (($back ?? null)?->isNotEmpty())
                    {{ $back }}
                @else
                    <div class="grow">
                    @isset($back)
                        <button type="button" {{ $back->attributes->merge([
                            'x-on:click' => 'close()',
                            'class' => 'bg-gray-200 w-max rounded-full cursor-pointer text-sm py-1 px-3 font-medium flex items-center gap-2 hover:ring-1 hover:ring-offset-2 hover:ring-gray-200',
                        ]) }}>
                            <x-icon name="back"/> {!! tr($back->attributes->get('label', 'app.label.back')) !!}
                        </button>
                    @else
                        <button type="button" x-on:click="close()" class="bg-gray-200 w-max rounded-full cursor-pointer text-sm py-1 px-3 font-medium flex items-center gap-2 hover:ring-1 hover:ring-offset-2 hover:ring-gray-200">
                            <x-icon name="back"/> {!! tr('app.label.back') !!}
                        </button>
                    @endisset
                    </div>
                @endisset

                @isset ($buttons)
                    <div class="shrink-0 flex items-center flex-wrap gap-2">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>
        @endisset

        {{ $slot }}
    </div>
</div>