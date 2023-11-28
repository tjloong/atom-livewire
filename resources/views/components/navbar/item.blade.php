<a
    class="{{ $attributes->get('class', 'py-1.5 px-3 flex items-center justify-center gap-2 font-medium text-gray-800') }}"
    {{ $attributes->except('icon', 'label', 'class') }}>
    @if ($icon = $attributes->get('icon'))
        <x-icon :name="$icon" class="opacity-70"/>
    @elseif ($logo = $attributes->get('logo'))
        <x-logo :name="$logo" size="20"/>
    @endif

    @if ($label = $attributes->get('label')) {{ tr($label) }}
    @else {{ $slot }}
    @endif
</a>
