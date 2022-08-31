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
    {{ $attributes->except('class') }}
>
    <div x-show="show" x-transition.opacity class="modal">
        <div class="modal-bg"></div>
        <div class="modal-container" x-on:click="close()">
            <div
                x-on:click.stop
                {{ $attributes->class([
                    'modal-content',
                    'max-w-lg' => !$attributes->get('class'),
                ]) }}
            >
                <div class="px-6 pt-4 flex items-center justify-between">
                    @if ($header = $attributes->get('header'))
                        <div class="font-semibold text-lg">{{ __($header) }}</div>
                    @elseif (isset($header))
                        <div class="font-semibold text-lg">{{ $header }}</div>
                    @endif

                    <a class="text-gray-500 flex items-center justify-center" x-on:click.prevent="close()">
                        <x-icon name="x"/>
                    </a>
                </div>

                <div class="p-6">
                    {{ $slot }}
                </div>

                @isset($foot)
                    <div class="p-4 bg-gray-100">
                        {{ $foot }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</div>

