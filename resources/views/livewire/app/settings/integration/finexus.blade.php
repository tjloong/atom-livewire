<div class="max-w-screen-md">
    <x-heading title="app.label.finexus-settings"/>

    <x-form>
        <x-group>
            <x-form.text wire:model.defer="settings.finexus_merchant_id" label="Merchant ID"/>
            <x-form.text wire:model.defer="settings.finexus_secret_key" label="Secret Key"/>
            <x-form.text wire:model.defer="settings.finexus_terminal_id" label="Terminal ID"/>
        </x-group>

        <x-slot:foot>
            <x-button.submit/>
            <x-button icon="link" label="app.label.test-connection" wire:click="test"/>
        </x-slot:foot>
    </x-form>
</div>