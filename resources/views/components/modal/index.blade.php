@props([
    'el' => $attributes->get('form') ? 'form' : 'div',
    'bgclose' => $attributes->get('bgclose', true),
])

<div
    x-data="{
        show: false,
        config: {
            bgclose: @js($bgclose),
        },
        open () { this.show = true },
        close () { this.show = false },
        onBgClose (e) {
            if (!this.config.bgclose) return
            if (e.target.getAttribute('x-ref') !== 'container') return
            this.$dispatch(@js($uid.'-close'))
        }
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close()"
    x-cloak
    x-bind:class="show && 'inset-0 z-50'"
    class="fixed"
>
    <div class="fixed inset-0 bg-black/80"></div>

    <div 
        x-ref="container"
        x-on:click="onBgClose" 
        class="absolute inset-0 overflow-auto px-6 py-10"
    >
        <{{ $el }} {{ 
            $attributes
                ->class([
                    'bg-white rounded-xl border shadow-lg mx-auto',
                    $attributes->get('class', 'max-w-lg'),
                ])
                ->merge([
                    'wire:submit.prevent' => $el === 'form' ? 'submit' : null,
                ])
                ->except(['uid', 'header', 'form'])
        }}>
            <div class="p-4 flex items-center justify-between border-b">
                @isset($header)
                    <div class="font-semibold text-lg">{{ $header }}</div>
                @elseif ($header = $attributes->get('header'))
                    <div class="flex items-center gap-3">
                        @if ($icon = $attributes->get('icon')) <x-icon :name="$icon" class="text-gray-400"/> @endif
                        <div class="font-semibold text-lg">{{ __($header) }}</div>
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

