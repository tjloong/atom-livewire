<div class="flex flex-col divide-y">
    <div class="p-4">
        @foreach (array_filter([
            'Subtotal' => currency($order->subtotal),
            'Shipping' => $order->shipping_rate_id ? currency($order->shipping_amount) : null,
        ]) as $key => $val)
            <div class="flex items-center gap-2 justify-between text-gray-500 font-medium">
                <div>{{ __($key) }}</div>
                <div>{{ $val }}</div>
            </div>
        @endforeach
    </div>

    <div class="p-4 flex items-center gap-4 justify-between text-xl font-semibold">
        <div>{{ __('Grand Total') }}</div>
        <div>{{ currency($order->grand_total) }}</div>
    </div>
</div>
