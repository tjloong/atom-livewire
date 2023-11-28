@php
    $href = $attributes->get('href');
    $target = $attributes->get('target', '_self');
    $label = $attributes->get('label') ?? $attributes->get('href');
    $icon = $attributes->get('icon');
    $caption = $attributes->get('caption') ?? $attributes->get('small');
    $text = $attributes->get('text', false);    
@endphp

<a @if (!empty($href)) href="{!! $href !!}" target="{{ $target }}" @endif
    class="{{ $attributes->get('class', 'font-medium text-blue-700') }}"
    {{ $attributes->except('class') }}>
    @if ($label)
        @if ($icon)
            <span class="flex items-center gap-2">
                <x-icon :name="$icon"/> {!! tr($label) !!}
            </span>
        @else
            {!! tr($label) !!}
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
