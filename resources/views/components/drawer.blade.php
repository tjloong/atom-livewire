@props([
    'id' => component_id($attributes, 'drawer'),
])

<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false },
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

    <div class="absolute top-0 bottom-0 right-0 left-0 p-4 md:left-auto md:w-1/2 lg:w-4/12 md:p-0">
        <div class="bg-white shadow-lg rounded-lg border flex flex-col w-full h-full overflow-hidden md:rounded-none">
            <div class="shrink-0 p-4 flex items-center justify-between gap-3 border-b">
                @isset($header) 
                    {{ $header }}
                @else
                    <div class="flex items-center gap-2">
                        @if ($icon = $attributes->get('icon'))
                            <x-icon :name="$icon" class="text-gray-400"/>
                        @endif

                        @if ($header = $attributes->get('header'))
                            <div class="text-lg font-semibold">
                                {{ __($header) }}
                            </div>
                        @endif
                    </div>
                @endif

                <div class="shrink-0">
                    <x-close x-on:click="$dispatch('close')"/>
                </div>
            </div>

            <div class="grow overflow-auto">
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
