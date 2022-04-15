<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Gkash Settings</x-slot>
        
        <div class="p-5">
            <x-input.text wire:model.defer="settings.gkash_mid" :error="$errors->first('settings.gkash_mid')" required>
                {{ _('GKash Merchant ID') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.gkash_signature_key" :error="$errors->first('settings.gkash_signature_key')" required>
                {{ __('GKash Signature Key') }}
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>