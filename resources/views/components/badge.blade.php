@props([
    'colors' => [
        'green' => 'text-green-800 bg-green-100 border border-green-200',
        'red' => 'text-red-800 bg-red-100 border border-red-200',
        'blue' => 'text-blue-800 bg-blue-100 border border-blue-200',
        'yellow' => 'text-yellow-800 bg-yellow-100 border border-yellow-200',
        'indigo' => 'text-indigo-800 bg-indigo-100 border border-indigo-200',
        'orange' => 'text-orange-800 bg-orange-100 border border-orange-200',
        'black' => 'text-white bg-black',
        'gray' => 'text-gray-800 bg-gray-100',
    ],
    'status' => [
        'due' => 'red',
        'error' => 'red',
        'failed' => 'red',

        'new' => 'yellow',
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
        'certified' => 'green',
        'delivered' => 'green',
        'published' => 'green',
        'completed' => 'green',
        'onboarded' => 'green',
        'offered' => 'green',
        'accepted' => 'green',

        'blocked' => 'black',
        'trashed' => 'black',
        'voided' => 'black',
    ],
    'label' => $attributes->get('label'),
])

<span {{ $attributes->class([
    'px-2 inline-flex text-sm leading-6 font-semibold rounded-full',
    $attributes->get('color')
        ? $colors[$attributes->get('color')]
        : ($colors[$status[$label ?? strip_tags(strtolower($slot->toHtml()))] ?? 'gray'])
]) }} class="bg-gray-100 text-gray-800">
    @if ($label) {{ __($label) }}
    @else {{ $slot }}
    @endif
</span>