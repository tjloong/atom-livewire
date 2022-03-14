@props([
    'colors' => [
        'green' => 'text-green-800 bg-green-100',
        'red' => 'text-red-800 bg-red-100',
        'blue' => 'text-blue-800 bg-blue-100',
        'yellow' => 'text-yellow-800 bg-yellow-100',
        'indigo' => 'text-indigo-800 bg-indigo-100',
        'black' => 'text-white bg-black',
        'gray' => 'text-gray-800 bg-gray-100',
    ],
    'status' => [
        'due' => 'red',
        'error' => 'red',
        'failed' => 'red',

        'admin' => 'yellow',
        'unpaid' => 'yellow',
        'opened' => 'yellow',
        'queueing' => 'yellow',
        'submitted' => 'yellow',
        'checked-out' => 'yellow',

        'ready' => 'blue',
        'default' => 'blue',
        'partial' => 'blue',
        'pending' => 'blue',
        'shipped' => 'blue',
        'feedback' => 'blue',
        'processing' => 'blue',

        'paid' => 'green',
        'sent' => 'green',
        'active' => 'green',
        'billed' => 'green',
        'closed' => 'green',
        'success' => 'green',
        'invoiced' => 'green',
        'verified' => 'green',
        'delivered' => 'green',
        'published' => 'green',

        'blocked' => 'black',
        'trashed' => 'black',
    ],
])

<span {{ $attributes->class([
    'px-2 inline-flex text-sm leading-5 font-semibold rounded-full',
    $attributes->get('color')
        ? $colors[$attributes->get('color')]
        : ($colors[$status[$slot->toHtml()] ?? 'gray'])
]) }}

class=" bg-gray-100 text-gray-800">
    {{ $slot }}
</span>