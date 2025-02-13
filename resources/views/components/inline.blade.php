@php
$gap = $attributes->get('gap', 2);
$wrap = $attributes->get('wrap', true);
$label = $attributes->get('label');
$icon = $attributes->get('icon');
$tooltip = $attributes->get('tooltip');
$except = ['label', 'icon', 'gap', 'wrap', 'tooltip'];
@endphp

<div 
    @if($tooltip) x-tooltip="{!! js(t($tooltip)) !!}" @endif
    {{ $attributes->class(array_filter([
        'inline-flex items-center',
        $wrap ? 'flex-wrap' : null,
        !$icon && !$label ? '*:shrink-0' : null,
        pick([
            'gap-1' => $gap === 1,
            'gap-2' => $gap === 2,
            'gap-3' => $gap === 3,
            'gap-4' => $gap === 4,
            'gap-5' => $gap === 5,
        ]),
    ]))->except($except) }}>
    @if ($icon && $label)
        <div class="shrink-0 flex">
            <x-icon :name="$icon" class="text-gray-400 m-auto"/>
        </div>
        <span class="grow">
            {!! tr($label) !!}
        </span>
    @elseif ($icon)
        <div class="shrink-0 flex">
            <x-icon :name="$icon" class="m-auto"/> 
        </div>
        <div class="grow">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>