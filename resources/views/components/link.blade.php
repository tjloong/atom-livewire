@props([
    'href' => $attributes->get('href'),
    'target' => $attributes->get('target', '_self'),
    'label' => $attributes->get('label') ?? $attributes->get('href'),
    'icon' => $attributes->get('icon'),
    'small' => $attributes->get('small'),
    'size' => $attributes->get('size'),
])

<a 
    @if (!empty($href))
        href="{!! $href !!}"
        target="{{ $target }}"
    @endif
    
    {{ $attributes->class([
        'font-medium text-blue-700', 
        $size === 'sm' ? 'text-sm' : null,
        $attributes->get('class')
    ])->only('class') }}
    {{ $attributes->except('class') }}
>
    @if ($label) 
        @if ($icon)
            <span class="flex items-center gap-2">
                <x-icon :name="$icon" :size="$size === 'sm' ? '11' : '14'"/> {{ __($label) }}
            </span>
        @else
            {{ __($label) }}
        @endif

        @if ($small) <br><span class="text-gray-500 font-medium {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}">{{ __($small) }}</span> @endif
    @elseif ($icon)
        <x-icon :name="$icon" :size="$attributes->get('size', '14')"/>
    @elseif ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($href)
        {{ $href }}
    @endif
</a>
