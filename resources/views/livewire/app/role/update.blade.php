<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$this->role->name" back>
        @can('role.manage')
            <x-button color="gray" label="Duplicate" wire:click="duplicate"/>

            <x-button.delete inverted
                title="Delete Role"
                message="This will DELETE the role. Are you sure?"
            />
        @endcan
    </x-page-header>

    <div class="flex flex-col gap-6">
        @livewire(atom_lw('app.role.form'), compact('role'))

        @module('permissions')
            @livewire(atom_lw('app.permission.form'), compact('role'))
        @endmodule
    </div>
</div>