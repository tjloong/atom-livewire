<a {{ $attributes->merge([
    'class' => '
        inline-flex items-center justify-center gap-2
        py-1.5 text-gray-800 font-medium hover:text-theme 
        md:px-3 md:hover:bg-gray-100 md:justify-start
    '
]) }}>
    @if ($icon = $attributes->get('icon'))
        <x-icon name="{{ $icon }}" size="20px" class="text-gray-400" type="{{ $attributes->get('icon-type') ?? 'regular' }}"/>
    @endif
    {{ $slot }}
</a>
