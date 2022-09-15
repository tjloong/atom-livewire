<form wire:submit.prevent="submit" class="p-5 grid gap-6">
    <x-form.text 
        label="Stripe Public Key"
        wire:model.defer="settings.stripe_public_key" 
        :error="$errors->first('settings.stripe_public_key')" 
        required
    />

    <x-form.text 
        label="Stripe Secret Key"
        wire:model.defer="settings.stripe_secret_key" 
        :error="$errors->first('settings.stripe_secret_key')" 
        required
    />

    <x-form.text 
        label="Stripe Webhook Signing Secret"
        wire:model.defer="settings.stripe_webhook_signing_secret" 
    />

    <div>
        <x-button.submit/>
    </div>
</form>