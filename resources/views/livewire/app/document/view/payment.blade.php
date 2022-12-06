<div>
    <x-box header="Payments">
        <div class="grid divide-y">
            <div class="max-h-[150px] overflow-auto">
                @foreach ($this->payments as $payment)
                    <div class="py-2 px-4 text-sm hover:bg-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="grow">
                                <a wire:click="open(@js($payment->id))" class="font-medium">
                                    {{ $payment->number }}
                                </a>    
                            </div>
                            
                            <div class="shrink-0 text-gray-500 font-medium">
                                {{ currency($payment->amount, $document->currency) }}
                            </div>
        
                            <div class="shrink-0">
                                <x-close.delete
                                    title="Delete Payment"
                                    message="Are you sure to delete this payment?"
                                    :params="$payment->id"
                                />
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

            <div class="p-4">
                <x-button icon="plus" color="gray" block
                    :label="[
                        'invoice' => 'Receive Payment',
                        'bill' => 'Issue Payment',
                    ][$document->type]"
                    wire:click="openPaymentFormModal"
                />
            </div>
        </div>
    </x-box>

    @livewire(lw('app.document.view.payment-form-modal'))
</div>
