<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">iPay88 Settings</x-slot>
        
        <div class="p-5">
            <x-input.text wire:model.defer="settings.ipay_merchant_code" :error="$errors->first('settings.ipay_merchant_code')" required>
                {{ _('iPay88 Merchant Code') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.ipay_merchant_key" :error="$errors->first('settings.ipay_merchant_key')" required>
                {{ __('iPay88 Merchant Key') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.ipay_url" :error="$errors->first('settings.ipay_url')" required>
                {{ __('iPay88 URL') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.ipay_query_url" :error="$errors->first('settings.ipay_query_url')" required>
                {{ __('iPay88 Re-query URL') }}
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>