@php
    $icon = $attributes->get('icon');
    $label = $attributes->get('label');
@endphp

<a {{ $attributes->merge([
    'class' => '
        inline-flex items-center justify-center gap-3 
        py-3 px-5 text-gray-800 font-medium
        md:hover:bg-gray-100 md:justify-start
    '
]) }}>
    @if ($icon !== false)
        <x-icon :name="$icon ?? $label" size="16" class="text-gray-400"/>
    @endif

    @if ($label) {{ __($label) }}
    @else {{ $slot }}
    @endif

    @if ($badgeText = data_get($badge, 'text'))
        <div class="w-5 h-5 rounded-full flex items-center justify-center font-medium text-xs {{ data_get($badge, 'colors.'.data_get($badge, 'color')) }}">
            {{ $badgeText }}
        </div>
    @endif
</a>
