<div class="max-w-screen-md">
    <x-heading title="atom::revenue-monster.heading.settings"/>

    <x-form>
        <x-form.group>
            <x-form.text label="atom::revenue-monster.label.client-id"
                wire:model.defer="settings.revenue_monster_client_id"/>
            <x-form.text label="atom::revenue-monster.label.client-secret"
                wire:model.defer="settings.revenue_monster_client_secret"/>
            <x-form.text label="atom::revenue-monster.label.store-id"
                wire:model.defer="settings.revenue_monster_store_id"/>
            <x-form.textarea label="atom::revenue-monster.label.private-key"
                wire:model.defer="settings.revenue_monster_private_key"/>
            <x-form.checkbox label="atom::revenue-monster.label.sandbox"
                wire:model="settings.revenue_monster_is_sandbox"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit/>
            <x-button label="atom::revenue-monster.button.test" icon="link"
                wire:click="test"/>
        </x-slot:foot>
    </x-form>
</div>