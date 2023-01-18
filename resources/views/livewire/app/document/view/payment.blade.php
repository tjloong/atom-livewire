<x-box header="Payments">
    <div class="grid divide-y">
        @if ($this->payments->count())
            <div class="max-h-[150px] overflow-auto">
                <div class="flex flex-col divide-y">
                    @foreach ($this->payments as $payment)
                        <div class="py-2 px-4 text-sm hover:bg-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="grow">
                                    <a href="{{ route('app.document.payment.update', [
                                        'documentId' => $payment->document_id,
                                        'documentPaymentId' => $payment->id,
                                    ]) }}" class="font-medium">
                                        {{ $payment->number }}
                                    </a>    
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
        @endif

        <div class="p-4">
            <x-button icon="plus" color="gray" block
                :label="[
                    'invoice' => 'Receive Payment',
                    'bill' => 'Issue Payment',
                ][$document->type]"
                :href="route('app.document.payment.create', [$document->id])"
            />
        </div>
    </div>
</x-box>
