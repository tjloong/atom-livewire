<div
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false },
    }"
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close()"
>
    <div 
        x-data 
        x-show="show" 
        x-transition.opacity 
        class="fixed inset-0 z-30"
    >
        <div
            x-on:click="$dispatch('{{ $uid }}-close')"
            class="absolute inset-0 bg-black/60">
        </div>

        <div class="absolute top-0 bottom-0 right-0 bg-white shadow-md overflow-auto w-10/12 md:max-w-md">
            <div class="flex flex-col divide-y">
                @isset($header)
                    {{ $header }}
                @else
                    <div class="flex items-center justify-between gap-3 p-4">
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

                        <x-close x-on:click="$dispatch('{{ $uid }}-close')"/>
                    </div>
                @endisset

                <div {{ $attributes->class([
                    'grow',
                    $attributes->get('class', 'p-6'),
                ]) }}>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
