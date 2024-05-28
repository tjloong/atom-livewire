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
            if (!$modal.isEmpty()) $modal.unlockScroll()
        },
    }"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-on:open-modal.window="id === $event.detail && open()"
    x-on:close-modal.window="id === $event.detail && close()"
    x-bind:class="show && 'active'"
    data-modal-id="{{ $id }}"
    class="modal fixed z-40 inset-0 flex items-center justify-center"
    {{ $attributes->except(['class', 'id', 'bgclose']) }}>
    <div
        x-on:dblclick.stop="bgclose === 'dblclick' && close()"
        x-on:click.stop="bgclose === true && close()"
        class="absolute inset-0 bg-black/60">
    </div>

    <div class="relative w-full p-3 {{ $attributes->get('class', 'max-w-screen-sm') }}">
        <div class="bg-white rounded-lg shadow-lg flex flex-col divide-y">
            @if ($slot->isNotEmpty())
                @isset($heading)
                    @if ($heading->isNotEmpty()) {{ $heading }}
                    @else
                        <x-heading lg class="p-4"
                            :icon="$heading->attributes->get('icon')"
                            :title="$heading->attributes->get('title')"
                            :subtitle="$heading->attributes->get('subtitle')"
                            :status="$heading->attributes->get('status')">
                            <x-close x-on:click.stop="close"/>
                        </x-heading>
                    @endif
                @endisset

                <div class="grow">
                    {{ $slot }}
                </div>

                @isset($foot)
                    <div {{ $foot->attributes->merge(['class' => 'shrink-0 bg-gray-100 rounded-b-lg']) }}>
                        {{ $foot }}
                    </div>
                @endisset
            @endif
        </div>
    </div>
</div>