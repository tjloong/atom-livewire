<a {{ $attributes->merge([
    'class' => '
        inline-flex items-center justify-center gap-2
        py-2 text-gray-800 font-medium hover:text-theme 
        md:px-4 md:hover:bg-gray-100 md:justify-start
    '
]) }}>
    @if ($icon = $attributes->get('icon'))
        <x-icon name="{{ $icon }}" size="20px" class="text-gray-400" type="{{ $attributes->get('icon-type') ?? 'regular' }}"/>
    @endif

    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</a>
