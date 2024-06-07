@php
$label = $attributes->get('label') ?? $attributes->get('href');
$icon = $attributes->get('icon');
$iconsuffix = $attributes->get('icon-suffix');
$align = $attributes->get('align');
$caption = $attributes->get('caption') ?? $attributes->get('small');
$color = $attributes->get('color', 'blue');
$color = pick([
    'text-black' => $color === 'black',
    'text-blue-500' => $color === 'blue',
]);
@endphp

<a {{ $attributes->class([
    pick([
        'flex items-center justify-center' => $align === 'center',
        'flex items-center justify-end' => $align === 'right',
    ]),
    $color,
])->except(['label', 'icon', 'align', 'caption']) }}>
    @if ($label)
        @if ($icon || $iconsuffix)
            <span class="inline-flex items-center gap-2 underline decoration-dotted">
                @if ($icon) <x-icon :name="$icon" class="shrink-0"/> @endif
                {!! tr($label) !!}
                @if ($iconsuffix) <x-icon :name="$iconsuffix" class="shrink-0"/> @endif
            </span>
        @else
            <span class="underline decoration-dotted">{!! tr($label) !!}</span>
        @endif

        @if ($caption) <br><span class="text-gray-500 font-medium">{!! tr($caption) !!}</span> @endif
    @elseif ($icon)
        <x-icon :name="$icon"/>
    @elseif ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($href)
        {{ $href }}
    @endif
</a>
