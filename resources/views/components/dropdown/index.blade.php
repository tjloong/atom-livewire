@php
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$placement = $attributes->get('placement');
$closeOnSelect = $attributes->get('close-on-select', true);
$directive = $placement ? 'x-anchor.'.$placement : 'x-anchor';
@endphp

<div 
    x-cloak
    x-data="{
        open: false,
        closeOnSelect: @js($closeOnSelect),
    }"
    x-on:click.away="open = false"
    {{ $attributes->except(['icon', 'label', 'placement']) }}>
    <div x-ref="anchor" x-on:click.stop="open = true" class="inline-block cursor-pointer">
        @if (isset($anchor)) {{ $anchor }}
        @else
            <div class="flex items-center gap-2">
                @if ($icon) <x-icon :name="$icon"/> @endif
                @if ($label) {!! tr($label) !!} @endif
                <x-icon name="chevron-down" class="text-sm"/>
            </div>
        @endif
    </div>

    <div 
        x-show="open"
        {{ $directive }}.offset.4="$refs.anchor"
        x-transition.opacity.duration.300
        x-on:click.stop="closeOnSelect && (open = false)"
        class="bg-white z-10 border rounded-md shadow-lg max-w-md min-w-[250px]">
        {{ $slot }}
    </div>
</div>