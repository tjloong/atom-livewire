<div class="max-w-screen-md">
    <x-heading title="Revenue Monster Settings"/>

    <x-form>
        <x-form.group>
            <x-form.text wire:model.defer="settings.revenue_monster_client_id" label="Client ID"/>
            <x-form.text wire:model.defer="settings.revenue_monster_client_secret" label="Client Secret"/>
            <x-form.text wire:model.defer="settings.revenue_monster_store_id" label="Store ID"/>
            <x-form.textarea wire:model.defer="settings.revenue_monster_private_key" label="Private Key"/>
            <x-form.checkbox wire:model="settings.revenue_monster_is_sandbox" label="Sandbox"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit/>
            <x-button wire:click="test" label="Test Connection" icon="link"/>
        </x-slot:foot>
    </x-form>
</div>