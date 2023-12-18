@php
    $id = component_id($attributes, 'drawer');
    $show = $attributes->get('show', false);
    $bgclose = $attributes->get('bg-close', true);
@endphp

<div
    x-cloak
    x-data="{
        show: false,
        bgclose: @js($bgclose),
        open () {
            this.$el.style.zIndex = this.getZIndex()
            this.show = true
            documentBodyScrolling(false)
        },
        close () { 
            this.show = false
            this.$wire.set(@js('drawers.'.$id), false)
            documentBodyScrolling(true)
        },
        getZIndex () {
            const z = Array.from(document.querySelectorAll('.drawer'))
                .map(el => (window.getComputedStyle(el).getPropertyValue('z-index')))
                .map(n => +n)

            const max = Math.max(...z)

            return max + 1
        },
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $id }}-open.window="open()"
    x-on:{{ $id }}-close.window="close()"
    x-on:open.stop="open()"
    x-on:close.stop="close()"
    @if ($show) x-init="open" @endif
    id="{{ $id }}"
    class="drawer fixed inset-0 z-40"
    {{ $attributes->wire('open') }}
    {{ $attributes->wire('close') }}>
    @if ($this->isDrawerOpened($id))
        <div class="fixed inset-0 bg-black/80"
            x-on:dblclick.stop="bgclose === 'dblclick' && $dispatch('close')"
            x-on:click.stop="bgclose === true && $dispatch('close')"></div>

        <div class="absolute top-1 bottom-1 right-0 pl-2 {{ $attributes->get('class', 'max-w-screen-sm w-full') }}">
            <div class="bg-white rounded-l-lg shadow-lg flex flex-col h-full">
                <div class="shrink-0 bg-white py-3 px-6 flex items-center justify-between gap-3 border-b rounded-t-lg">
                    <div class="cursor-pointer" x-on:click="$dispatch('close')">
                        <x-icon name="arrow-right-long" class="text-lg"/>
                    </div>
    
                    <div class="flex items-center gap-2">
                        @isset($buttons)
                            @if (!$buttons->attributes->get('blank'))
                                {{ $buttons }}
    
                                @if ($buttons->attributes->get('archive'))
                                    <x-button.archive size="sm"
                                        :params="$buttons->attributes->get('archive')"/>
                                @endif
    
                                @if ($buttons->attributes->get('restore'))
                                    <x-button.restore size="sm"
                                        :params="$buttons->attributes->get('restore')"/>
                                @endif
    
                                @if ($buttons->attributes->get('trash'))
                                    <x-button.trash size="sm" inverted
                                        :params="$buttons->attributes->get('trash')"/>
                                @endif
    
                                @if ($buttons->attributes->get('delete'))
                                    <x-button.delete size="sm" inverted
                                        :params="$buttons->attributes->get('delete')"/>
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
                                            <x-dropdown.archive
                                                :params="$dropdown->attributes->get('archive')"/>
                                        @endif
    
                                        @if ($dropdown->attributes->get('restore'))
                                            <x-dropdown.restore
                                                :params="$dropdown->attributes->get('restore')"/>
                                        @endif
        
                                        @if ($dropdown->attributes->get('trash'))
                                            <x-dropdown.trash
                                                :params="$dropdown->attributes->get('trash')"/>
                                        @endif
        
                                        @if ($dropdown->attributes->get('delete'))
                                            <x-dropdown.delete
                                                :params="$dropdown->attributes->get('delete')"/>
                                        @endif
                                    </div>
                                @endif
                            </x-dropdown>
                        @endisset
                    </div>
                </div>
    
                @isset($heading)
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
                @endisset
    
                <div class="grow overflow-auto pb-5">
                    {{ $slot }}
                </div>
    
                @isset($foot)
                    <div class="shrink-0">
                        {{ $foot }}
                    </div>
                @endisset
            </div>
        </div>
    @endif
</div>
