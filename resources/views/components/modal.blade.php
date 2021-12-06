<div
    x-data="modal"
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close()"
    {{ $attributes->except('class') }}
>
    @isset($trigger)
        <div x-on:click="open()">
            {{ $trigger }}
        </div>
    @endisset

    <div x-show="show" x-transition.opacity class="modal">
        <div class="modal-bg"></div>
        <div class="modal-container" x-on:click="close()">
            <div
                x-on:click.stop
                {{ $attributes->class([
                    'modal-content',
                    'w-80' => !$attributes->get('class'),
                ]) }}
            >
                <div class="px-6 pt-4 flex items-center justify-between">
                    <div class="font-semibold text-lg">
                        {{ $title ?? '' }}
                    </div>

                    <a class="text-gray-500 flex items-center justify-center" x-on:click.prevent="close()">
                        <x-icon name="x"/>
                    </a>
                </div>

                <div class="p-6">
                    {{ $slot }}
                </div>

                @isset($buttons)
                    <div class="p-4 bg-gray-100">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</div>

