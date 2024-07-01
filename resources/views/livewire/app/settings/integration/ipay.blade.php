<div class="max-w-screen-md">
    <x-heading title="settings.heading.ipay" lg/>

    <x-form>
        <x-group cols="2">
            <x-input label="iPay88 Merchant Code" wire:model.defer="settings.ipay_merchant_code"/>
            <x-input label="iPay88 Merchant Key" wire:model.defer="settings.ipay_merchant_key"/>
            <x-input label="iPay88 URL" wire:model.defer="settings.ipay_url"/>
            <x-input label="iPay88 Re-query URL" wire:model.defer="settings.ipay_query_url"/>
        </x-group>
    </x-form>
</div>