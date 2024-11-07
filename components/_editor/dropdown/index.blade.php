@php
$icon = $icon ?? $attributes->get('icon');
$label = $attributes->get('label');
@endphp

<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false },
    }"
    x-on:click.away="close()">
    @isset ($anchor)
        {{ $anchor }}
    @else
        <x-editor.button
            :label="$label"
            x-ref="anchor"
            x-on:click="open()">
            @if ($icon instanceof \Illuminate\View\ComponentSlot)
                {!! $icon !!}
            @else
                <x-icon :name="$icon"/>
            @endif
        </x-editor.button>
    @endisset

    <div
        x-ref="dropdown"
        x-show="show"
        x-anchor.offset.6="$refs.anchor"
        x-transition.opacity.duration.100
        class="z-10 bg-white border border-gray-300 shadow-lg rounded-lg">
        <div class="flex flex-col divide-y first:*:rounded-t-lg last:*:rounded-b-lg">
            {{ $slot }}
        </div>
    </div>
</div>