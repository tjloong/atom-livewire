<x-modal uid="payment-form-modal" icon="comment-dollar" :header="$this->title">
    @if ($payment)
        <div class="grid gap-6">
            <x-form.date label="Payment Date"
                wire:model="payment.paid_at"
            />

            <x-form.amount label="Amount"
                wire:model.defer="payment.amount"
                :prefix="data_get($payment, 'currency')"
                :error="$errors->first('payment.amount')"
                required
            />

            @if (data_get($payment, 'currency') !== account_settings('default_currency'))
                <x-form.amount label="Currency Rate"
                    wire:model.defer="payment.currency_rate"
                />
            @endif

            <x-form.select label="Payment Method"
                wire:model="payment.paymode"
                :options="$this->paymodes"
                :error="$errors->first('payment.paymode')"
                required
            />
        </div>

        <x-slot:foot>
            <div class="flex items-center justify-between gap-2">
                <x-button.submit type="button"
                    wire:click="submit"
                />
    
                @if ($payment->id)
                    <x-button icon="download" color="gray" 
                        :label="[
                            'invoice' => 'Receipt',
                            'bill' => 'Payment Voucher',
                        ][$payment->document->type]"
                        wire:click="pdf"
                    />
                @endif
            </div>
        </x-slot:foot>
    @endif
</x-modal>
