<x-form header="Promotion Overview">
    <x-form.text
        label="Promotion Name"
        wire:model.defer="promotion.name"
        :error="$errors->first('promotion.name')"
        required
    />

    <x-form.text 
        label="Code"
        wire:model.defer="promotion.code" 
        :error="$errors->first('promotion.code')"
    />

    <x-form.select 
        label="Discount Type"
        wire:model="promotion.type" 
        :options="[
            ['value' => 'fixed', 'label' => 'Fixed Amount'],
            ['value' => 'percentage', 'label' => 'Percentage'],
        ]"
        :error="$errors->first('promotion.type')" 
        required
    />

    <x-form.number 
        label="Discount {{ $promotion->type === 'fixed' ? 'Amount' : 'Percentage' }}"
        wire:model.defer="promotion.rate" 
        :error="$errors->first('promotion.rate')" 
        required
    />

    <x-form.number
        label="Redemption Limit"
        wire:model.defer="promotion.usable_limit"
        unit="times"
    />

    <x-form.date 
        label="Expiry Date"
        wire:model.defer="promotion.end_at"
    />

    <x-form.textarea 
        label="Description"
        wire:model.defer="promotion.description"
    />

    <x-form.checkbox 
        label="This promo code is active"
        wire:model="promotion.is_active"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
