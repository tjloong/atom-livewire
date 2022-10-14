<form wire:submit.prevent="submit" class="p-5 grid gap-6">
    <x-form.text 
        label="iPay88 Merchant Code"
        wire:model.defer="settings.ipay_merchant_code" 
        :error="$errors->first('settings.ipay_merchant_code')" 
        required
    />

    <x-form.text 
        label="iPay88 Merchant Key"
        wire:model.defer="settings.ipay_merchant_key" 
        :error="$errors->first('settings.ipay_merchant_key')" 
        required
    />

    <x-form.text 
        label="iPay88 URL"
        wire:model.defer="settings.ipay_url" 
        :error="$errors->first('settings.ipay_url')" 
        required
    />

    <x-form.text 
        label="iPay88 Re-query URL"
        wire:model.defer="settings.ipay_query_url" 
        :error="$errors->first('settings.ipay_query_url')" 
        required
    />

    <div>
        <x-button.submit/>
    </div>
</form>