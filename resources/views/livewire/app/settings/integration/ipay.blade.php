<div class="max-w-screen-md">
    <x-heading title="settings.heading.ipay"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text label="iPay88 Merchant Code" wire:model.defer="settings.ipay_merchant_code"/>
            <x-form.text label="iPay88 Merchant Key" wire:model.defer="settings.ipay_merchant_key"/>
            <x-form.text label="iPay88 URL" wire:model.defer="settings.ipay_url"/>
            <x-form.text label="iPay88 Re-query URL" wire:model.defer="settings.ipay_query_url"/>
        </x-form.group>
    </x-form>
</div>