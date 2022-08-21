<x-form header="Role Information">
    <x-form.text
        label="Role Name"
        wire:model.defer="role.name"
        :error="$errors->first('role.name')"
        required
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
