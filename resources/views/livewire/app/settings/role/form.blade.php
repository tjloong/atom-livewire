<x-form id="role-form" :header="optional($role)->exists ? 'Update Role' : 'Create Role'" drawer>
    @if ($role)
        <x-slot:buttons>
            <x-button.submit size="sm"/>

            @if ($role->exists)
                <x-button.delete inverted size="sm" :label="false"
                    title="Delete Role"
                    message="This will DELETE the role. Are you sure?"
                />
            @endif
        </x-slot:buttons>

        @if ($role->exists && $role->is_admin)
            <x-form.group>
                <x-form.field label="Role Name" :value="$role->name"/>
            </x-form.group>
        @else
            <x-form.group>
                <x-form.text wire:model.defer="role.name" label="Role Name"/>
            </x-form.group>
        @endif        
    @endif
</x-form>