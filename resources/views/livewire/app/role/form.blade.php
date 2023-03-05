<x-form>
    @if ($role->exists && $role->is_admin)
        <x-form.group>
            <x-form.field label="Role Name">{{ $role->name }}</x-form.field>
        </x-form.group>
        <x-slot:foot></x-slot:foot>
    @else
        <x-form.group>
            <x-form.text
                label="Role Name"
                wire:model.defer="role.name"
                :error="$errors->first('role.name')"
                required
            />
        </x-form.group>
    @endif
</x-form>