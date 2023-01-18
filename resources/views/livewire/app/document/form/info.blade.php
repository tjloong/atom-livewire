<div class="p-5 pb-8 grid gap-6 md:grid-cols-2">
    <x-form.text label="Number"
        wire:model.lazy="inputs.postfix"
        :prefix="data_get($inputs, 'prefix')"
        :error="$errors->first('inputs.postfix')"
        placeholder="Leave empty to auto generate"
        required
    />

    <x-form.date label="Issue Date"
        wire:model="inputs.issued_at"
    />

    <x-form.text label="Reference"
        wire:model.lazy="inputs.reference"
    />

    @isset($inputs['data']['valid_for'])
        <x-form.number label="Valid For"
            wire:model.lazy="inputs.data.valid_for"
            postfix="day(s)"
        />
    @endisset

    @if (in_array($document->type, ['invoice', 'bill', 'delivery-order']))
        <x-form.select
            :label="[
                'invoice' => 'Quotation',
                'bill' => 'Purchase Order',
                'delivery-order' => 'Invoice',
            ][$document->type]"
            wire:model="inputs.converted_from_id"
            callback="getConvertFromDocuments"
        />
    @endif

    @if ($document->type === 'delivery-order')
        <x-form.date label="Delivered Date"
            wire:model="inputs.delivered_at"
        />

        <x-form.select label="Delivery Channel"
            wire:model="inputs.data.delivery_channel"
            :options="data_get($settings, 'delivery_order.channels', [])"
        />
    @elseif ($document->type === 'purchase-order')
        <x-form.textarea label="Deliver To"
            wire:model.lazy="inputs.data.deliver_to"
        />
    @else
        <x-form.text label="Payment Term"
            wire:model.lazy="inputs.payterm"
        />

        <x-form.text label="Description"
            wire:model.lazy="inputs.description"
        />
    @endif
</div>