<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Stripe Settings</x-slot>
        
        <div class="p-5">
            <x-input.text wire:model.defer="settings.stripe_public_key" :error="$errors->first('settings.stripe_public_key')" required>
                {{ __('Stripe Public Key') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.stripe_secret_key" :error="$errors->first('settings.stripe_secret_key')" required>
                {{ __('Stripe Secret Key') }}
            </x-input.text>
        
            <x-input.text wire:model.defer="settings.stripe_webhook_signing_secret" :error="$errors->first('settings.stripe_webhook_signing_secret')" required>
                {{ __('Stripe Webhook Signing Secret') }}
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>