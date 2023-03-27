@props([
    'href' => $attributes->get('href'),
    'target' => $attributes->get('target', '_self'),
    'label' => $attributes->get('label') ?? $attributes->get('href'),
    'icon' => $attributes->get('icon'),
])

<a href="{!! $href !!}"
    target="{{ $target }}"
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
    @else {{ $slot }}
    @endif
</a>
