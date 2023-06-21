@props([
    'totals' => $attributes->get('totals') ?? $attributes->get('total'),
    'currency' => $attributes->get('currency'),
])

<div class="flex flex-col gap-1">
    @if (is_numeric($totals))
        <div class="grid grid-cols-2 text-lg font-bold">
            <div class="md:text-right">{{ __('TOTAL') }}</div>
            <div class="text-right">{{ currency($totals, $currency) }}</div>    
        </div>
    @else
        @foreach ($totals as $key => $val)
            @if ($key === array_key_last($totals))
                <div class="grid grid-cols-2 text-lg font-bold">
                    <div class="md:text-right">{{ __(str()->upper($key)) }}</div>
                    <div class="text-right">{{ currency($val, $currency) }}</div>    
                </div>
            @else
                <div class="grid grid-cols-2 font-medium text-gray-500">
                    <div class="md:text-right">{{ __(str()->upper($key)) }}</div>
                    <div class="text-right">{{ currency($val, $currency) }}</div>
                </div>
            @endif
        @endforeach
    @endif
</div>