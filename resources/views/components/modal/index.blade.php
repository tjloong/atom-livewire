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
        x-on:click="close()"
        class="fixed inset-0 z-50 bg-black/80 overflow-auto"
    >
        <div class="px-6 py-10">
            <{{ $el }} x-on:click.stop {{ 
                $attributes->merge([
                    'class' => collect([
                        'mx-auto bg-white rounded-xl border shadow-lg',
                        !$attributes->get('class') ? 'max-w-lg' : null,
                    ])->filter()->join(' '),
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
                        <x-icon name="x"/>
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

