@props([
    'sizes' => [
        'text' => [
            'xs' => 'text-xs px-1.5',
            'sm' => 'text-sm px-2',
            'md' => 'text-base px-3',
        ],
        'icon' => [
            'xs' => '10',
            'sm' => '12',
            'md' => '14',
        ],
    ],
    'label' => $attributes->get('label'),
    'icon' => $attributes->get('icon'),
])

<span {{ $attributes->class([
    'px-2 inline-flex items-center gap-2 font-semibold rounded-full',    

    data_get($sizes, 'text.'.$attributes->get('size', 'sm')),

    collect(get_status_color(
        $attributes->get('color')
        ?? $label 
        ?? strip_tags(strtolower($slot->toHtml()))
    ))->values()->join(' '),
]) }}>
    @if ($icon) 
        <x-icon 
            :name="$icon" 
            :size="data_get($sizes, 'icon.'.$attributes->get('size', 'sm'))"
        /> 
    @endif

    @if ($label) {!! __($label) !!}
    @else {{ $slot }}
    @endif
</span>