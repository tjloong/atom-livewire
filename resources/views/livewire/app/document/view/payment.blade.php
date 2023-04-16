<x-box header="Payments">
    <x-slot:buttons>
        <x-button icon="plus" color="gray" size="xs"
            :label="[
                'invoice' => 'Receive',
                'bill' => 'Issue',
            ][$document->type]"
            :href="route('app.document.payment.create', [$document->id])"
        />
    </x-slot:buttons>

    <div class="flex flex-col divide-y">
        @if ($this->payments->count())
            <div class="max-h-[150px] overflow-auto">
                <div class="flex flex-col divide-y">
                    @foreach ($this->payments as $payment)
                        <div class="py-2 px-4 text-sm hover:bg-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="grow">
                                    <x-link :href="route('app.document.payment.update', [$payment->id])" :label="$payment->number"/>
                                </div>
                                
                                <div class="shrink-0 text-gray-500 font-medium">
                                    {{ currency($payment->amount, $document->currency) }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="shrink-0">
                                    {{ format_date($payment->paid_at) }}
                                </div>

                                <div class="font-medium text-gray-500">
                                    {{ $payment->paymode }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <x-empty-state size="xs" title="No Payment" subtitle="No payment found"/>
        @endif
    </div>
</x-box>
