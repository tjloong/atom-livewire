<a {{ 
    $attributes->class([
        'py-1.5 px-3 flex items-center justify-center gap-2 font-medium',
        $attributes->get('class', 'text-gray-800 hover:text-theme'),
    ])->except(['icon', 'label']) 
}}>
    @if ($icon = $attributes->get('icon'))
        <x-icon :name="$icon" class="opacity-70"/>
    @endif

    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</a>
