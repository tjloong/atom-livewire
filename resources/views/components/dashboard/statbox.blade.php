@props([
    'title' => $attributes->get('title'),
    'subtitle' => $attributes->get('subtitle'),
    'count' => $attributes->get('count'),
    'amount' => $attributes->get('amount'),
    'currency' => $attributes->get('currency'),
    'percentage' => $attributes->get('percentage'),
])

<div {{ $attributes->class([
    'p-5 bg-white rounded-xl border shadow grid gap-1.5',
    $attributes->get('class'),
]) }}>
    <div class="grid">
        @if ($title)
            <div class="font-semibold text-gray-500">{{ __($title) }}</div>
        @endif

        @if ($subtitle)
            <div class="font-medium text-sm text-gray-400">{{ __($subtitle) }}</div>
        @endif
    </div>

    <div {{ $attributes->merge(['class' => 'text-4xl font-bold']) }}>
        @if ($currency && !is_null($amount))
            <div class="flex gap-2">
                <div class="text-lg font-medium text-gray-400">{{ $currency }}</div>
                {{ currency($amount) }}
            </div>
        @elseif (!is_null($amount))
            {{ currency($amount) }}
        @elseif (!is_null($percentage))
            {{ number_format($percentage, 2) }}%
        @elseif (!is_null($count))
            {{ $count }}
        @elseif (!$slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>
</div>