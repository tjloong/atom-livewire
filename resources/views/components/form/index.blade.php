@props([
    'fields' => $attributes->get('fields'),
    'uid' => $attributes->get('uid', 'form'),
    'modal' => $attributes->get('modal'),
])

<form 
    id="{{ $uid }}"
    wire:submit.prevent="{{ $attributes->get('submit', 'submit') }}"
    @if ($modal)
        x-data="{
            show: false,
            open () { this.show = true },
            close (e, force = false) {
                if (force || !e.target.closest('#form-modal-container')) {
                    this.show = false
                }
            },
        }"
        x-show="show"
        x-transition.opacity
        x-on:{{ $uid }}-open.window="open()"
        x-on:{{ $uid }}-close.window="close(null, true)"
        x-bind:class="show && 'inset-0 z-50'"
        x-cloak
        class="fixed inset-0 z-50"
    @endif
>
    @if ($modal) <div class="fixed inset-0 bg-black/80"></div> @endif

    <div 
        @if ($modal && $attributes->get('on-bg-close', true)) x-on:click="close" @endif
        class="{{ $modal ? 'absolute inset-0 overflow-auto px-6 py-10' : '' }}"
    >
        <div id="form-modal-container" {{ $attributes
            ->merge(['class' => 'bg-white shadow border rounded-xl'])
            ->merge(['class' => $modal ? 'relative max-w-screen-sm mx-auto' : ''])
        }}>
            @if ($header = $attributes->get('header'))
                <div class="m-1 py-3 px-4 text-lg font-bold border-b">
                    {{ __($header) }}
                </div>
            @elseif (isset($header))
                <div {{ $header->attributes->class(['m-1']) }}>
                    {{ $header }}
                </div>
            @endif

            @if ($modal)
                <div class="absolute -top-4 -right-4 bg-white border shadow rounded-full p-1">
                    <x-close x-on:click="close(null, true)"/>
                </div>
            @endif

            <div class="p-1">
                {{ $slot }}
            </div>
        
            @isset($errorAlert)
                <div class="p-4">{{ $errorAlert }}</div>
            @elseif ($errors && $errors->any())
                <div class="p-4"><x-alert :errors="$errors"/></div>
            @endisset
        
            @isset($foot)
                @if ($foot->isNotEmpty())
                    <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
                        {{ $foot }}
                    </div>
                @endif
            @else
                <div class="py-4 px-6 bg-gray-100 rounded-b-lg">
                    <x-button.submit/>
                </div>
            @endisset
        </div>
    </div>
</form>
