<x-form header="Stripe Settings">
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

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>