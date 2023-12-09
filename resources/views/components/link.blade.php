@php
    $label = $attributes->get('label') ?? $attributes->get('href');
    $icon = $attributes->get('icon');
    $caption = $attributes->get('caption') ?? $attributes->get('small');
@endphp

<a {{ $attributes }}>
    @if ($label)
        @if ($icon)
            <span class="flex items-center gap-2 font-medium text-blue-700">
                <x-icon :name="$icon"/> {!! tr($label) !!}
            </span>
        @else
            <span class="font-medium text-blue-700">{!! tr($label) !!}</span>
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
