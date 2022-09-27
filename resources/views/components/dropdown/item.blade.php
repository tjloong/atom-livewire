@props([
    'label' => $label ?? $attributes->get('label'),
    'icon' => $icon ?? $attributes->get('icon'),
])

@if ($attributes->has('href') || $attributes->wire('click')->value())
    <a {{ $attributes->merge([
        'class' => 'py-3 px-5 flex items-center gap-3 text-gray-800 hover:bg-gray-100'
    ])->except(['label', 'icon']) }}>
        @if (is_string($icon)) <x-icon :name="$icon ?? (is_string($label) ? $label : null)" class="text-gray-400"/>
        @elseif ($icon) {{ $icon }}
        @endif

        @if (is_string($label)) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </a>
@else
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@endif
