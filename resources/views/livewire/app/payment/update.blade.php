<div class="max-w-screen-md mx-auto">
    <x-page-header :title="'Payment #'.$payment->number" :status="$payment->status" back>
        @if (in_array($payment->status, ['failed', 'draft']))
            <x-button.delete inverted
                title="Delete Payment"
                message="Are you sure to DELETE this payment?"
            />
        @endif
    </x-page-header>

    <x-box>
        <div class="flex flex-col divide-y">
            @foreach ([
                'Date' => format_date($payment->created_at, 'datetime'),
                'Payment #' => $payment->number,
                'Order #' => $payment->order->number,
                'Mode' => $payment->mode,
                'Status' => ['status' => $payment->status],
            ] as $key => $val)
                <x-field :label="$key"
                    :value="is_string($val) ? $val : null"
                    :badge="data_get($val, 'status')"
                />
            @endforeach

            <div class="p-4 flex items-center justify-between gap-4 text-lg font-semibold">
                <div>{{ __('Amount') }}</div>
                <div>{{ currency($payment->amount, $payment->currency) }}</div>
            </div>
        </div>
    </x-box>
</div>