@props([
    'count' => $attributes->get('count'),
    'amount' => $attributes->get('amount'),
    'currency' => $attributes->get('currency'),
    'percentage' => $attributes->get('percentage'),
])

<div class="p-5 bg-white rounded-md border shadow grid gap-1.5">
    @if ($title = $attributes->get('title'))
        <div class="font-semibold text-gray-500">
            {{ __($title) }}
        </div>
    @endif

    <div {{ $attributes->merge(['class' => 'text-5xl font-bold']) }}>
        @if ($currency && $amount)
            <div class="flex gap-2">
                <div class="text-lg font-medium text-gray-400">{{ $currency }}</div>
                {{ currency($amount) }}
            </div>
        @elseif ($amount)
            {{ currency($amount) }}
        @elseif ($percentage)
            {{ number_format($percentage, 2) }}%
        @elseif ($count)
            {{ $count }}
        @elseif (!$slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>
</div>