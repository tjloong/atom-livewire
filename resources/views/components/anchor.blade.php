@php
    $label = $attributes->get('label') ?? $attributes->get('href');
    $icon = $attributes->get('icon');
    $align = $attributes->get('align');
    $caption = $attributes->get('caption') ?? $attributes->get('small');
@endphp

<a 
    {{ $attributes->class([
        pick([
            'flex items-center justify-center' => $align === 'center',
            'flex items-center justify-end' => $align === 'right',
        ]),
    ])->except(['label', 'icon', 'align', 'caption']) }}>
    @if ($label)
        @if ($icon)
            <span class="inline-flex items-center gap-2 text-blue-500 underline decoration-dotted">
                <x-icon :name="$icon" class="shrink-0"/>
                {!! tr($label) !!}
            </span>
        @else
            <span class="text-blue-500 underline decoration-dotted">{!! tr($label) !!}</span>
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
