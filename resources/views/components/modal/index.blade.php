<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close (e, force = false) {
            if (force || !e.target.closest('#modal-container')) {
                this.show = false
            }
        },
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $uid }}-open.window="open()"
    x-on:{{ $uid }}-close.window="close(null, true)"
    x-bind:class="show && 'inset-0 z-50'"
    class="fixed"
>
    <div class="fixed inset-0 bg-black/80"></div>

    <div 
        @if ($attributes->get('on-bg-close', true)) x-on:click="close" @endif
        class="absolute inset-0 overflow-auto px-6 py-10"
    >
        <div id="modal-container" {{ $attributes->class([
            'bg-white rounded-xl border shadow mx-auto relative mx-auto',
            $attributes->get('class', 'max-w-screen-sm'),
        ])->only('class') }}>
            @if ($header = $attributes->get('header'))
                <div class="m-1 py-3 px-4 text-lg font-bold border-b">
                    {{ __($header) }}
                </div>
            @elseif (isset($header))
                <div {{ $header->attributes->class(['m-1']) }}>
                    {{ $header }}
                </div>
            @endif

            <div class="absolute -top-4 -right-4 bg-white border shadow rounded-full p-1">
                <x-close x-on:click="close(null, true)"/>
            </div>

            <div class="p-1">
                {{ $slot }}
            </div>

            @isset($foot)
                <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
                    {{ $foot }}
                </div>
            @endisset
        </div>
    </div>
</div>

