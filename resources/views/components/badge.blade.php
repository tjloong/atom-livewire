@props([
    'sizes' => [
        'xs' => 'text-xs px-1.5',
        'sm' => 'text-sm px-2',
        'md' => 'text-base px-3',
    ],
    'label' => $attributes->get('label'),
])

<span {{ $attributes->class([
    'px-2 inline-flex font-semibold rounded-full',    

    data_get(
        $sizes, 
        $attributes->get('size', 'sm')
    ),

    collect(get_status_color(
        $attributes->get('color')
        ?? $label 
        ?? strip_tags(strtolower($slot->toHtml()))
    ))->values()->join(' '),
]) }}>
    @if ($label) {{ __($label) }}
    @else {{ $slot }}
    @endif
</span>