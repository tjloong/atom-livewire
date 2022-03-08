<div class="max-w-lg mx-auto">
    <x-page-header :title="$role->name" back>
        <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
            title: 'Delete Role',
            message: 'Are you sure to delete this role?',
            type: 'error',
            onConfirmed: () => $wire.delete(),    
        })">
            Delete
        </x-button>
    </x-page-header>

    <div class="grid gap-6">
        @livewire('atom.role.form', ['role' => $role], key('role-form'))
    
        @module('permissions')
            <x-box>
                <x-slot name="header">Role's Permissions</x-slot>
                @livewire('atom.permission.listing', ['role' => $role], key('role-permissions'))
            </x-box>
        @endmodule
    </div>
</div>