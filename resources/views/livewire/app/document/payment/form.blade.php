<x-form>
    <x-form.group cols="2">
        <x-form.date wire:model="payment.paid_at" label="Payment Date"/>
        <x-form.number wire:model.defer="payment.amount" :prefix="data_get($payment, 'currency')" step=".01"/>
        <x-form.select wire:model="payment.paymode" label="Payment Method" :options="data_get($this->options, 'paymodes')"/>
    </x-form.group>
</x-form>
