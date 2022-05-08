<x-form header="Gkash Settings">
    <x-form.text 
        label="GKash Merchant ID"
        wire:model.defer="settings.gkash_mid" 
        :error="$errors->first('settings.gkash_mid')" 
        required
    />

    <x-form.text 
        label="'GKash Signature Key"
        wire:model.defer="settings.gkash_signature_key" 
        :error="$errors->first('settings.gkash_signature_key')" 
        required
    />

    <x-form.text 
        label="'GKash URL"
        wire:model.defer="settings.gkash_url" 
        :error="$errors->first('settings.gkash_url')" 
        required
    />

    <x-form.text 
        label="'GKash Sandbox URL"
        wire:model.defer="settings.gkash_sandbox_url"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>