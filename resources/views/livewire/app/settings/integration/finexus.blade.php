<div class="max-w-screen-md">
    <x-heading title="app.label.finexus-settings" lg/>

    <x-form>
        <x-group>
            <x-input wire:model.defer="settings.finexus_merchant_id" label="Merchant ID"/>
            <x-input wire:model.defer="settings.finexus_secret_key" label="Secret Key"/>
            <x-input wire:model.defer="settings.finexus_terminal_id" label="Terminal ID"/>
            <x-input wire:model.defer="settings.finexus_url" label="URL"/>
            <x-input wire:model.defer="settings.finexus_query_url" label="Query URL"/>
        </x-group>

        <x-slot:foot>
            <x-button action="submit"/>
            <x-button icon="link" label="app.label.test-connection" wire:click="test"/>
        </x-slot:foot>
    </x-form>
</div>