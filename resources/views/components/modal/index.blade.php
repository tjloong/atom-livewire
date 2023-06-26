@props([
    'id' => component_id($attributes, 'modal'),
])

<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false},
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $id }}-open.window="open"
    x-on:{{ $id }}-close.window="close"
    x-on:open="open"
    x-on:close="close"
    class="fixed inset-0 z-50 overflow-auto"
    id="{{ $id }}"
>
    @if ($attributes->get('bg-close', true)) <x-modal.overlay x-on:click="close"/>
    @else <x-modal.overlay/>
    @endif

    <div class="relative mx-auto py-10 px-4 {{ [
        'sm' => 'max-w-screen-sm',
        'md' => 'max-w-screen-md',
        'lg' => 'max-w-screen-lg',
    ][$attributes->get('size', 'sm')] }}">
        <div class="bg-white rounded-lg shadow-lg flex flex-col divide-y">
            <div class="flex items-center justify-between p-4">
                <div class="grow">
                    @if ($header = $attributes->get('header'))
                        <div class="text-lg font-bold flex items-center gap-3">
                            @if ($icon = $attributes->get('icon')) <x-icon :name="$icon" class="text-gray-400"/> @endif
                            {!! __($header) !!}
                        </div>
                    @elseif (isset($header))
                        {{ $header }}
                    @endif
                </div>

                <div class="shrink-0">
                    <x-close x-on:click.stop="close"/>
                </div>
            </div>

            <div class="grow">
                {{ $slot }}
            </div>

            @isset($foot)
                <div class="shrink-0 p-4 bg-slate-100 rounded-b-lg">
                    {{ $foot }}
                </div>
            @endisset
        </div>
    </div>
</div>
