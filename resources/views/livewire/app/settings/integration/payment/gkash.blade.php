<form wire:submit.prevent="submit" class="p-5 grid gap-6">
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

    <div>
        <x-button action="submit"/>
    </div>
</form>