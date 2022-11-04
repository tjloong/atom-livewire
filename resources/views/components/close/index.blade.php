@props([
    'colors' => [
        'gray' => 'text-gray-500 hover:bg-gray-200',
        'red' => 'text-red-500 hover:bg-red-100',
        'blue' => 'text-blue-500 hover:bg-blue-100',
        'green' => 'text-green-500 hover:bg-green-100',
        'yellow' => 'text-yellow-500 hover:bg-yellow-100',
        'black' => 'text-black hover:bg-black hover:text-white',
    ][$attributes->get('color', 'gray')],
])

<div {{ $attributes->class([
    'inline-flex p-1 rounded-full cursor-pointer',
    $colors,
    $attributes->get('class'),
]) }}>
    <x-icon name="xmark"/>
</div>
