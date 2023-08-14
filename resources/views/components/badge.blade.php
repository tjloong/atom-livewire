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
    'getColor' => function() use ($attributes) {
        return [
            'green' => 'bg-green-100 text-green-600 border border-green-200',
            'red' => 'bg-red-100 text-red-600 border border-red-200',
            'blue' => 'bg-blue-100 text-blue-600 border border-blue-200',
            'yellow' => 'bg-yellow-100 text-yellow-600 border border-yellow-200',
            'indigo' => 'bg-indigo-100 text-indigo-600 border border-indigo-200',
            'orange' => 'bg-orange-100 text-orange-600 border border-orange-200',
            'black' => 'bg-black text-white',
            'gray' => 'bg-gray-100 text-gray-800 border',
        ][$attributes->get('color') ?? 'gray'];
    },
])

<span {{ $attributes->class([
    'px-2 inline-flex items-center gap-2 font-semibold rounded-full',    
    data_get($sizes, 'text.'.$attributes->get('size', 'sm')),
    $getColor(),
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