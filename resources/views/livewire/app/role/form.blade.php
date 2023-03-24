<x-form>
    @if ($role->exists && $role->is_admin)
        <x-form.group>
            <x-form.field label="Role Name">{{ $role->name }}</x-form.field>
        </x-form.group>
        <x-slot:foot></x-slot:foot>
    @else
        <x-form.group>
            <x-form.text wire:model.defer="role.name" label="Role Name"/>
        </x-form.group>
    @endif
</x-form>