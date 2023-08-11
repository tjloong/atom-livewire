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
>
    @if ($attributes->get('bg-close') === false) <x-modal.overlay/>
    @elseif ($attributes->get('bg-close') === 'dblclick') <x-modal.overlay x-on:dblclick.stop="$dispatch('close')"/>
    @else <x-modal.overlay x-on:click.stop="$dispatch('close')"/>
    @endif

    <div class="absolute top-0 bottom-0 right-0 left-0 py-1 pl-2 md:left-auto {{ [
        'sm' => 'md:w-1/2 lg:w-2/12',
        'md' => 'md:w-1/2 lg:w-4/12',
        'lg' => 'md:w-2/3 lg:w-1/2',
        'xl' => 'md:w-4/5',
        'full' => 'md:w-11/12',
    ][$size] }}">
        <div {{ $attributes->class([
            'shadow-lg rounded-l-lg border flex flex-col gap-4 w-full h-full',
            $attributes->get('class', 'bg-white'),
        ])->except(['id', 'size', 'show', 'status', 'header', 'title', 'subtitle', 'icon']) }}>
            <div class="shrink-0 bg-white py-3 px-6 flex items-center justify-between gap-3 border-b rounded-t-lg">
                <div class="cursor-pointer" x-on:click="$dispatch('close')">
                    <x-icon name="arrow-right-long" class="text-lg"/>
                </div>

                @isset($buttons)
                    {{ $buttons }}
                @endisset
            </div>

            <div class="grow flex flex-col overflow-auto">
                <div class="shrink-0">
                    @isset($header) {{ $header }}
                    @elseisset($title) {{ $title }}
                    @else
                        <div class="flex items-center gap-4 px-6 py-3">
                            <div class="flex items-center text-xl gap-2">
                                @if ($icon = $attributes->get('icon'))
                                    <x-icon :name="$icon" class="text-gray-400 shrink-0"/>
                                @endif

                                @if ($header = $attributes->get('header') ?? $attributes->get('title'))
                                    <div>
                                        <div class="font-bold">
                                            {{ __($header) }}
                                        </div>
                                        
                                        @if ($subtitle = $attributes->get('subtitle'))
                                            <div class="font-medium text-gray-500 text-sm">
                                                {{ __($subtitle) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if ($status = $attributes->get('status'))
                                @if (is_string($status)) <x-badge :label="$status"/>
                                @elseif (is_array($status))
                                    @foreach ($status as $key => $val)
                                        <x-badge :label="$val" :color="$key" size="lg"/>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    @endisset
                </div>

                <div class="grow">
                    {{ $slot }}
                </div>
            </div>

            @isset($foot)
                <div class="shrink-0">
                    {{ $foot }}
                </div>
            @endisset
        </div>
    </div>
</div>
