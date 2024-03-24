@php
    $gap = $attributes->get('gap', 2);
    $label = $attributes->get('label');
    $icon = $attributes->get('icon');
    $except = ['label', 'icon', 'gap'];
@endphp

<div {{ $attributes->class([
    'flex items-center',
    pick([
        'gap-1' => $gap === 1,
        'gap-2' => $gap === 2,
        'gap-3' => $gap === 3,
        'gap-4' => $gap === 4,
        'gap-5' => $gap === 5,
    ]),
    $attributes->get('class'),
])->except($except) }}>
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