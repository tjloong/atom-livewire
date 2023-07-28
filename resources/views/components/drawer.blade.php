@props([
    'id' => component_id($attributes, 'drawer'),
    'size' => $attributes->get('size', 'md'),
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
    id="{{ $id }}"
    class="fixed inset-0 z-40"
>
    @if ($attributes->get('bg-close') === false) <x-modal.overlay/>
    @else <x-modal.overlay x-on:click.stop="$dispatch('close')"/>
    @endif

    <div class="absolute top-0 bottom-0 right-0 left-0 py-1 pl-2 md:left-auto {{ [
        'sm' => 'md:w-1/2 lg:w-2/12',
        'md' => 'md:w-1/2 lg:w-4/12',
        'lg' => 'md:w-2/3 lg:w-1/2',
    ][$size] }}">
        <div {{ $attributes->class([
            'shadow-lg rounded-l-lg border flex flex-col w-full h-full',
            $attributes->get('class', 'bg-white'),
        ]) }}>
            <div class="shrink-0 bg-white py-3 px-6 flex items-center justify-between gap-3 border-b">
                <div class="cursor-pointer" x-on:click="$dispatch('close')">
                    <x-icon name="arrow-right-long" class="text-lg"/>
                </div>

                @isset($buttons)
                    <div class="flex items-center gap-2">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>

            <div class="grow flex flex-col overflow-auto">
                <div class="shrink-0">
                    @isset($header) {{ $header }}
                    @else
                        <div class="flex items-center gap-2 text-xl px-6 py-3">
                            @if ($icon = $attributes->get('icon'))
                                <x-icon :name="$icon" class="text-gray-400"/>
                            @endif
    
                            @if ($header = $attributes->get('header'))
                                <div class="font-bold">
                                    {{ __($header) }}
                                </div>
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
