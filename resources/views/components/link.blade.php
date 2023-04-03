@props([
    'href' => $attributes->get('href'),
    'target' => $attributes->get('target', '_self'),
    'label' => $attributes->get('label') ?? $attributes->get('href'),
    'icon' => $attributes->get('icon'),
    'small' => $attributes->get('small'),
])

<a 
    @if (!empty($href))
        href="{!! $href !!}"
        target="{{ $target }}"
    @endif
    
    {{ $attributes->class(['font-medium text-blue-700 print:text-gray-800', $attributes->get('class')])->only('class') }}
    {{ $attributes->except('class') }}
>
    @if ($label) 
        @if ($icon)
            <span class="flex items-center gap-2">
                <x-icon :name="$icon" size="14"/> {{ __($label) }}
            </span>
        @else
            {{ __($label) }}
        @endif

        @if ($small) <br><span class="text-gray-500 text-sm font-medium">{{ __($small) }}</span> @endif
    @elseif ($icon)
        <x-icon :name="$icon" :size="$attributes->get('size', '14')"/>
    @else 
        {{ $slot }}
    @endif
</a>
