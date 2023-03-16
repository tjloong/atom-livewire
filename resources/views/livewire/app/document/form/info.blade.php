<x-form.group cols="2">
    <x-form.text wire:model.lazy="inputs.postfix" label="Number" 
        :prefix="data_get($inputs, 'prefix')"
        placeholder="Leave empty to auto generate"
    />

    <x-form.date wire:model="inputs.issued_at" label="Issue Date"/>
    <x-form.text wire:model.lazy="inputs.reference"/>

    @isset($inputs['data']['valid_for'])
        <x-form.number wire:model.lazy="inputs.data.valid_for" postfix="day(s)"/>
    @endisset

    @if (in_array($document->type, ['invoice', 'bill', 'delivery-order']))
        <x-form.select wire:model="inputs.converted_from_id" :label="$this->sourceLabel" callback="getSources"/>
    @endif

    @if ($document->type === 'delivery-order')
        <x-form.date wire:model="inputs.delivered_at" label="Delivered Date"/>
        <x-form.select wire:model="inputs.data.delivery_channel" :options="data_get($settings, 'delivery_order.channels', [])"/>
    @elseif ($document->type === 'purchase-order')
        <x-form.textarea wire:model.lazy="inputs.data.deliver_to"/>
    @else
        <x-form.text wire:model.lazy="inputs.payterm" label="Payment Term"/>
        <x-form.text wire:model.lazy="inputs.description"/>
    @endif
</x-form.group>