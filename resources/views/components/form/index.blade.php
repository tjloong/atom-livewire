@props([
    'fields' => $attributes->get('fields'),
    'modal' => $attributes->get('modal'),
    'id' => component_id($attributes, 'form'),
])

<form 
    id="{{ $id }}"
    
    @if ($confirm = $attributes->get('confirm'))
        x-data
        x-on:submit.prevent="$dispatch('confirm', {
            title: @js(__(data_get($confirm, 'title', 'Submit Form'))),
            message: @js(__(data_get($confirm, 'message', 'Are you sure to submit this form?'))),
            onConfirmed: () => $wire.call(@js($attributes->get('submit', 'submit'))),
        })"
    @else
        wire:submit.prevent="{{ $attributes->get('submit', 'submit') }}"
    @endif

    @if ($modal)
        x-data="{
            show: false,
            open () { this.show = true },
            close (e, force = false) {
                if (force || !e.target.closest('#form-modal-container')) {
                    this.show = false
                    this.$dispatch('close')
                }
            },
        }"
        x-show="show"
        x-transition.opacity
        x-on:{{ $id }}-open.window="open()"
        x-on:{{ $id }}-close.window="close(null, true)"
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
            ->only('class')
        }}>
            @if ($header = $attributes->get('header'))
                <div class="m-1 py-3 px-4 border-b flex flex-wrap items-center gap-3">
                    <div class="grow text-lg font-bold flex items-center gap-3">
                        @if ($icon = $attributes->get('icon')) <x-icon :name="$icon" class="text-gray-400"/> @endif
                        {{ __($header) }}
                    </div>

                    @isset($buttons)
                        <div class="shrink-0 flex items-center gap-3">
                            {{ $buttons }}
                        </div>
                    @endisset
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
                @if ($errorAlert->isNotEmpty())
                    <div class="p-4">{{ $errorAlert }}</div>
                @endif
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
