@php
    $id = $attributes->get('id') ?? $this->getName() ?? $this->id;
    $bgclose = $attributes->get('bg-close', true);
    $render = $slot->isNotEmpty() && isset($this->isModalOpened) && $this->isModalOpened;
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        bgclose: @js($bgclose),
        show: @entangle('isModalOpened'),

        open () {
            this.show = true
            this.$dispatch('open')
            $layering.zindex()
            $layering.lockScroll()
        },

        close () {
            this.show = false
            this.$dispatch('close')
            if (!$layering.isEmpty()) $layering.unlockScroll()
        },
    }"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-on:open-modal.window="id === $event.detail && open()"
    x-on:close-modal.window="id === $event.detail && close()"
    x-bind:class="show && 'active'"
    data-modal-id="{{ $id }}"
    class="modal fixed z-40 inset-0 flex items-center justify-center"
    {{ $attributes->wire('close') }}>
    <div
        x-on:dblclick.stop="bgclose === 'dblclick' && close()"
        x-on:click.stop="bgclose === true && close()"
        class="absolute inset-0 bg-black/60">
    </div>

    <div class="relative w-full p-3 {{ $attributes->get('class', 'max-w-screen-sm') }}">
        <div class="bg-white rounded-lg shadow-lg flex flex-col divide-y">
            @if ($render)
                @isset($heading)
                    @if ($heading->isNotEmpty()) {{ $heading }}
                    @else
                        <x-heading lg class="p-4"
                            :icon="$heading->attributes->get('icon')"
                            :title="$heading->attributes->get('title')"
                            :subtitle="$heading->attributes->get('subtitle')">
                            <x-close x-on:click.stop="close"/>
                        </x-heading>
                    @endif
                @endisset

                <div class="grow">
                    {{ $slot }}
                </div>

                @isset($foot)
                    <div class="shrink-0 bg-gray-100 rounded-b-lg">
                        {{ $foot }}
                    </div>
                @endisset
            @endif
        </div>
    </div>
</div>