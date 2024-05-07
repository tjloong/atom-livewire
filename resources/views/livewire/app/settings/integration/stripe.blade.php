<div class="max-w-screen-md">
    <x-heading title="app.label.stripe-settings"/>

    <x-form>
        <x-group>
            <x-form.text wire:model.defer="settings.stripe_public_key" label="app.label.stripe-public-key"/>
            <x-form.text wire:model.defer="settings.stripe_secret_key" label="app.label.stripe-secret-key"/>
            <x-form.text wire:model.defer="settings.stripe_webhook_signing_secret" label="app.label.stripe-webhook-secret"/>
        </x-group>
    </x-form>
</div>