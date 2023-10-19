<div class="max-w-screen-md">
    <x-heading title="atom::stripe.heading.settings"/>

    <x-form>
        <x-form.group>
            <x-form.text label="atom::stripe.label.public-key"
                wire:model.defer="settings.stripe_public_key"/>

            <x-form.text label="atom::stripe.label.secret-key"
                wire:model.defer="settings.stripe_secret_key"/>

            <x-form.text label="atom::stripe.label.webhook-secret"
                wire:model.defer="settings.stripe_webhook_signing_secret" />
        </x-form.group>
    </x-form>
</div>