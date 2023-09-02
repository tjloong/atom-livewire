@props([
    'id' => component_id($attributes, 'drawer'),
    'size' => $attributes->get('size', 'md'),
    'show' => $attributes->get('show', false),
])

<div
    x-cloak
    x-data="{
        show: false,
        open () { 
            this.show = true
            documentBodyScrolling(false)
        },
        close () { 
            this.show = false
            documentBodyScrolling(true)
        },
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $id }}-open.window="open()"
    x-on:{{ $id }}-close.window="close()"
    x-on:open="open()"
    x-on:close="close()"
    @if ($show) x-init="open" @endif
    id="{{ $id }}"
    class="fixed inset-0 z-40"
    {{ $attributes->wire('open') }}
    {{ $attributes->wire('close') }}
>
    @if ($attributes->get('bg-close') === false) <x-modal.overlay/>
    @elseif ($attributes->get('bg-close') === 'dblclick') <x-modal.overlay x-on:dblclick.stop="$dispatch('close')"/>
    @else <x-modal.overlay x-on:click.stop="$dispatch('close')"/>
    @endif

    <div class="absolute top-0 bottom-0 right-0 left-0 py-1 pl-2 md:left-auto">
        <div class="bg-white shadow-lg rounded-l-lg border flex flex-col h-full">
            <div class="shrink-0 bg-white py-3 px-6 flex items-center justify-between gap-3 border-b rounded-t-lg">
                <div class="cursor-pointer" x-on:click="$dispatch('close')">
                    <x-icon name="arrow-right-long" class="text-lg"/>
                </div>

                <div class="flex items-center gap-2">
                    @isset($buttons)
                        @if (!$buttons->attributes->get('blank'))
                            {{ $buttons }}

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
                        <x-dropdown placement="bottom-end" size="sm"
                            :label="$dropdown->attributes->get('label', 'More')">
                            {{ $dropdown }}

                            @if (
                                $dropdown->attributes->get('restore')
                                || $dropdown->attributes->get('trash')
                                || $dropdown->attributes->get('delete')
                            )
                                <div class="border-t">
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

            <div {{ $attributes->class([
                'grow overflow-auto md:w-screen',
                $attributes->get('class', 'p-5 max-w-screen-sm'),
            ])->only('class') }}>
                {{ $slot }}
            </div>

            @isset($foot)
                <div class="shrink-0">
                    {{ $foot }}
                </div>
            @endisset
        </div>
    </div>
</div>
