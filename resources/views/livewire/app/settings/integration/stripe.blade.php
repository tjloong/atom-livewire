<div class="max-w-screen-md">
    <x-heading title="app.label.stripe-settings" lg/>

    <x-form>
        <x-inputs>
            <x-input wire:model.defer="settings.stripe_public_key" label="app.label.stripe-public-key"/>
            <x-input wire:model.defer="settings.stripe_secret_key" label="app.label.stripe-secret-key"/>

            @if ($webhook = get($settings, 'stripe_webhook_signing_secret'))
                <x-input :value="$webhook" label="app.label.stripe-webhook-secret" readonly/>
            @endif
        </x-inputs>

        <x-slot:foot>
            <x-button action="submit"/>

            @if (get($settings, 'stripe_public_key') && get($settings, 'stripe_public_key'))
                <x-button action="test" label="app.label.test-connection" icon="link"/>
            @endif
        </x-slot:foot>
    </x-form>
</div>