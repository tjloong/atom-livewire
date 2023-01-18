<div class="grid p-4 md:grid-cols-2">
    <div></div>
    <div class="bg-slate-100 rounded-lg">
        <div class="py-2 px-6 flex items-center justify-between gap-2">
            <div class="font-medium">{{ __('Subtotal') }}</div>
            <div class="font-medium">{{ currency($document->subtotal, $document->currency) }}</div>
        </div>

        @foreach ($document->getTaxes() as $tax)
            <div class="px-6 flex items-center justify-between gap-2 text-sm">
                <div>{{ data_get($tax, 'label') }}</div>
                <div class="font-medium">{{ currency(data_get($tax, 'amount')) }}</div>
            </div>
        @endforeach

        <div class="bg-slate-200 rounded-md m-2 py-2 px-4">
            <div class="flex items-center justify-between gap-2">
                <div class="font-bold">{{ __('Grand Total') }}</div>
                <div class="font-bold">{{ currency($document->grand_total, $document->currency) }}</div>
            </div>

            @auth
                @if ($document->is_foreign_currency)
                    <div class="text-sm text-right font-medium text-gray-500">
                        {{ currency($document->calculateCurrencyConversion('grand_total'), $document->master_currency) }}
                    </div>
                @endif
            @endauth
        </div>

        @if ($document->splitted_total)
            <div class="bg-slate-200 rounded-md m-2 py-2 px-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="font-bold">{{ __('Amount to be Paid') }}</div>
                    <div class="font-bold">{{ currency($document->splitted_total, $document->currency) }}</div>
                </div>

                @auth
                    @if ($document->is_foreign_currency)
                        <div class="text-sm text-right font-medium text-gray-500">
                            {{ currency($document->calculateCurrencyConversion('splitted_total'), $document->master_currency) }}
                        </div>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
