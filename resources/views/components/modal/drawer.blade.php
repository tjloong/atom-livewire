@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$show = $attributes->get('show', false);
$bgclose = $attributes->get('bg-close', true);
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        show: @js($show),
        bgclose: @js($bgclose),

        open () {
            if (this.show) return
            this.show = true
            this.$dispatch('open')
            $modal.zindex()
            $modal.lockScroll()
        },

        close () {
            if (!this.show) return
            this.show = false
            this.$dispatch('close')
            if ($modal.isEmpty()) $modal.unlockScroll()
            $modal.unlockScroll()
        },
    }"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-on:open-modal.window="id === $event.detail && open()"
    x-on:close-modal.window="id === $event.detail && close()"
    x-on:keydown.escape.stop="close()"
    x-bind:class="show && 'active'"
    data-modal-id="{{ $id }}"
    class="modal-drawer fixed inset-0 z-40"
    {{ $attributes->except(['class', 'id']) }}>
    <div
        x-on:dblclick.stop="bgclose === 'dblclick' && close()"
        x-on:click.stop="bgclose === true && close()"
        class="fixed inset-0 bg-black/80 z-40">
    </div>

    <div class="fixed top-1 bottom-1 right-0 z-50 pl-2 w-full {{ $attributes->get('class', 'max-w-screen-sm') }}">
        <div class="bg-white rounded-l-lg shadow-lg overflow-hidden flex flex-col h-full">
            <div class="shrink-0 bg-white py-3 px-6 flex flex-wrap items-center justify-between gap-3 border-b rounded-t-lg">
                <div class="cursor-pointer" x-on:click="close()">
                    @isset($back) {{ $back }}
                    @else <x-icon name="arrow-right-long" class="text-lg"/>
                    @endif
                </div>

                @if ($slot->isNotEmpty() && isset($buttons) && $buttons->isNotEmpty())
                    <div {{ $buttons->attributes->class([$buttons->attributes->get('class', 'flex items-center gap-2 flex-wrap')]) }}>
                        {{ $buttons }}
                    </div>
                @endif
            </div>

            @if ($slot->isNotEmpty())
                @isset($heading)
                    <div {{ $heading->attributes->class([
                        'shrink-0 border-b',
                        $heading->attributes->get('class', 'px-5 pt-3'),
                    ])->except(['icon', 'title', 'subtitle', 'status']) }}>
                        @if ($heading->isNotEmpty()) {{ $heading }}
                        @else
                            <x-heading
                                :icon="$heading->attributes->get('icon')"
                                :title="$heading->attributes->get('title')"
                                :subtitle="$heading->attributes->get('subtitle')"
                                :status="$heading->attributes->get('status')">
                            </x-heading>
                        @endif
                    </div>
                @endisset

                <div class="modal-drawer-body grow overflow-auto pb-5">
                    {{ $slot }}
                </div>
    
                @if (isset($foot) && $foot->isNotEmpty())
                    <div class="shrink-0">
                        {{ $foot }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>