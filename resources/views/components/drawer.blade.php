@php
    $id = $attributes->get('id') ?? $this->id;
    $show = $attributes->get('show', false);
    $bgclose = $attributes->get('bg-close', true);
    $render = $slot->isNotEmpty() && isset($this->isDrawerOpened) && $this->isDrawerOpened;
@endphp

<div
    x-cloak
    x-data="{
        id: @js($id),
        show: @js($show),
        bgclose: @js($bgclose),

        open () {
            this.show = true
            $layering.zindex()
            document.body.style.overflow = 'hidden'
        },

        close () {
            this.show = false
            if ($layering.isEmpty()) document.body.style.overflow = 'auto'
        },
    }"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-on:open-drawer.window="id === $event.detail && open()"
    x-on:close-drawer.window="id === $event.detail && close()"
    x-on:open="open()"
    x-on:close="close()"
    x-bind:class="show && 'active'"
    class="drawer fixed inset-0 z-40"
    {{ $attributes->wire('close') }}>
    <div
        x-on:dblclick.stop="bgclose === 'dblclick' && $dispatch('close')"
        x-on:click.stop="bgclose === true && $dispatch('close')"
        class="fixed inset-0 bg-black/80 z-40">
    </div>

    <div class="fixed top-1 bottom-1 right-0 z-50 pl-2 w-full {{ $attributes->get('class', 'max-w-screen-sm') }}">
        <div class="bg-white rounded-l-lg shadow-lg overflow-hidden flex flex-col h-full">
            <div class="shrink-0 bg-white py-3 px-6 flex flex-wrap items-center justify-between gap-3 border-b rounded-t-lg">
                <div class="cursor-pointer" x-on:click="$dispatch('close')">
                    <x-icon name="arrow-right-long" class="text-lg"/>
                </div>

                @if ($render)
                    <div x-data class="flex flex-wrap items-center gap-2">
                        @isset($buttons)
                            @if (!$buttons->attributes->get('blank'))
                                {{ $buttons }}

                                @if ($buttons->attributes->get('archive'))
                                    <x-button.archive sm :params="$buttons->attributes->get('archive')"/>
                                @endif

                                @if ($buttons->attributes->get('restore'))
                                    <x-button.restore sm :params="$buttons->attributes->get('restore')"/>
                                @endif

                                @if ($buttons->attributes->get('trash'))
                                    <x-button.trash sm inverted :params="$buttons->attributes->get('trash')"/>
                                @endif

                                @if ($buttons->attributes->get('delete'))
                                    <x-button.delete sm inverted :params="$buttons->attributes->get('delete')"/>
                                @endif
                            @endif
                        @endisset

                        @isset($dropdown)
                            <x-dropdown placement="bottom-end">
                                <x-slot:anchor>
                                    <x-button sm :label="$dropdown->attributes->get('label', 'More')" icon="chevron-down" position="end"/>
                                </x-slot:anchor>

                                {{ $dropdown }}

                                @if (
                                    $dropdown->attributes->get('restore')
                                    || $dropdown->attributes->get('trash')
                                    || $dropdown->attributes->get('delete')
                                    || $dropdown->attributes->get('archive')
                                )
                                    <div class="border-t">
                                        @if ($dropdown->attributes->get('archive'))
                                            <x-dropdown.archive :params="$dropdown->attributes->get('archive')"/>
                                        @endif

                                        @if ($dropdown->attributes->get('restore'))
                                            <x-dropdown.restore :params="$dropdown->attributes->get('restore')"/>
                                        @endif
        
                                        @if ($dropdown->attributes->get('trash'))
                                            <x-dropdown.trash :params="$dropdown->attributes->get('trash')"/>
                                        @endif
        
                                        @if ($dropdown->attributes->get('delete'))
                                            <x-dropdown.delete :params="$dropdown->attributes->get('delete')"/>
                                        @endif
                                    </div>
                                @endif
                            </x-dropdown>
                        @endisset
                    </div>
                @endif
            </div>

            @if ($render && isset($heading))
                <div {{ $heading->attributes->class([
                    'shrink-0 border-b',
                    $heading->attributes->get('class', 'px-5 pt-3'),
                ])->except(['icon', 'title', 'subtitle', 'status']) }}>
                    @if ($heading->isNotEmpty())
                        {{ $heading }}
                    @else
                        <x-heading 
                            :icon="$heading->attributes->get('icon')"
                            :title="$heading->attributes->get('title')"
                            :subtitle="$heading->attributes->get('subtitle')"
                            :status="$heading->attributes->get('status')"/>
                    @endif
                </div>
            @endif

            @if ($render)
                <div class="grow overflow-auto pb-5">
                    {{ $slot }}
                </div>

                @isset($foot)
                    <div class="shrink-0">
                        {{ $foot }}
                    </div>
                @endisset
            @endif
        </div>
    </div>
</div>