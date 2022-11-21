<x-modal uid="role-form-modal" icon="user-tag" :header="optional($role)->exists ? 'Update Role' : 'Create Role'">
    <x-form.text
        label="Role Name"
        wire:model.defer="role.name"
        :error="$errors->first('role.name')"
        required
    />

    <x-slot:foot>
        <x-button.submit type="button" wire:click="submit"/>
    </x-slot:foot>
</x-modal>