<x-form>
    <x-form.date label="Payment Date"
        wire:model="payment.paid_at"
    />

    <x-form.number label="Amount"
        wire:model.defer="payment.amount"
        :prefix="data_get($payment, 'currency')"
        :error="$errors->first('payment.amount')"
        required
    />

    <x-form.select label="Payment Method"
        wire:model="payment.paymode"
        :options="$this->paymodes"
        :error="$errors->first('payment.paymode')"
        required
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
