<a {{ $attributes->merge([
    'class' => '
        inline-flex items-center justify-center gap-3 
        py-3 px-5 text-gray-800 font-medium
        md:hover:bg-gray-100 md:justify-start
    '
]) }}>
    @if ($icon = $attributes->get('icon'))
        <x-icon :name="$icon" 
            size="18px" 
            class="text-gray-400" 
        />
    @endif

    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif

    @if ($badgeText = data_get($badge, 'text'))
        <div class="w-5 h-5 rounded-full flex items-center justify-center font-medium text-xs {{ data_get($badge, 'colors.'.data_get($badge, 'color')) }}">
            {{ $badgeText }}
        </div>
    @endif
</a>
