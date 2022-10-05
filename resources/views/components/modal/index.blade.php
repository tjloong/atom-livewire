@props(['el' => $attributes->get('form') ? 'form' : 'div'])

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
        x-show="show" 
        x-transition.opacity 
        class="fixed inset-0 z-50 overflow-auto"
    >
        <div
            x-on:click="$dispatch('{{ $uid }}-close')"
            class="absolute inset-0 bg-black/80">
        </div>

        <div class="absolute left-1/2 -translate-x-1/2 px-6 py-10 w-full mx-auto {{ $attributes->get('class', 'max-w-lg') }}">
            <{{ $el }} {{ 
                $attributes->merge([
                    'class' => 'bg-white rounded-xl border shadow-lg',
                    'wire:submit.prevent' => $el === 'form' ? 'submit' : null,
                ])->except(['uid', 'header', 'form']) 
            }}>
                <div class="p-4 flex items-center justify-between border-b">
                    @if ($header = $header ?? $attributes->get('header'))
                        <div class="font-semibold text-lg">
                            {{ is_string($header) ? __($header) : $header }}
                        </div>
                    @endif

                    <x-close x-on:click="$dispatch('{{ $uid }}-close')"/>
                </div>

                <div class="p-6">
                    {{ $slot }}
                </div>

                @isset($foot)
                    <div class="p-4 bg-gray-100 rounded-b-xl">
                        {{ $foot }}
                    </div>
                @endisset
            </{{ $el }}>
        </div>
    </div>
</div>

