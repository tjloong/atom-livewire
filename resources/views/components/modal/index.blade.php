@props(['el' => $attributes->get('form') ? 'form' : 'div'])

<div
    x-data="{
        show: false,
        open () {
            document.documentElement.classList.add('overflow-hidden')
            this.show = true
        },
        close () {
            document.documentElement.classList.remove('overflow-hidden')
            this.show = false
        },
    }"
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close()"
>
    <div 
        x-show="show" 
        x-transition.opacity 
        class="fixed inset-0 z-50 overflow-auto"
    >
        <div x-on:click="close()" class="absolute inset-0 bg-black/80"></div>
        <div class="absolute left-1/2 -translate-x-1/2 px-6 py-10 w-full mx-auto {{ $attributes->get('class', 'max-w-lg') }}">
            <{{ $el }} {{ 
                $attributes->merge([
                    'class' => 'bg-white rounded-xl border shadow-lg',
                    'wire:submit.prevent' => $el === 'form' ? 'submit' : null,
                ])->except(['uid', 'header', 'form']) 
            }}>
                <div class="px-6 py-4 flex items-center justify-between border-b">
                    @if ($header = $header ?? $attributes->get('header'))
                        <div class="font-semibold text-lg">
                            {{ is_string($header) ? __($header) : $header }}
                        </div>
                    @endif

                    <a class="text-gray-500 flex items-center justify-center" x-on:click.prevent="close()">
                        <x-icon name="xmark"/>
                    </a>
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

