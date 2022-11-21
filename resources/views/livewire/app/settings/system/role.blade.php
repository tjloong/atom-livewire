<div class="max-w-screen-lg mx-auto">
    <x-table>
        <x-slot:header>
            <x-table.header label="Roles">
                <x-button size="sm" color="gray"
                    label="New Role"
                    wire:click="open('create')"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->roles->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            @module('permissions') <x-table.th label="Permissions" class="text-right"/> @endmodule
            <x-table.th label="Users" class="text-right"/>
            <x-table.th class="w-4"/>
        </x-slot:thead>

        @foreach ($this->roles as $role)
            <x-table.tr>
                @if ($role->slug === 'admin') <x-table.td :label="$role->name"/>
                @else <x-table.td :label="$role->name" wire:click="open('edit', {{ $role->id }})"/>
                @endif

                @module('permissions')
                    <x-table.td class="text-right">
                        @if ($role->slug === 'admin') {{ __('All') }}
                        @else
                            @php $permissionsCount = $role->permissions()->granted()->count() @endphp
                            <a wire:click="open('permission', { role_id: {{ $role->id }} })">
                                {{ __(
                                    ':count '.str()->plural('permission', $permissionsCount), 
                                    ['count' => $permissionsCount]
                                ) }}
                            </a>
                        @endif
                    </x-table.td>    
                @endmodule

                <x-table.td class="text-right">
                    <a wire:click="open('user', { role_id: {{ $role->id }} })">
                        {{ __(':count '.str()->plural('user', $role->users_count), ['count' => $role->users_count]) }}
                    </a>
                </x-table.td>

                @if ($role->slug === 'admin') <x-table.td label=" "/>
                @else
                    <x-table.td dropdown>
                        <x-dropdown.item label="Edit" icon="edit" wire:click="open('edit', {{ $role->id }})"/>
                        <x-dropdown.item label="Duplicate" icon="copy" wire:click="duplicate({{ $role->id }})"/>
                        <x-dropdown.delete 
                            title="Delete Role"
                            message="This will delete the role. Are you sure?"
                            :params="$role->id"
                        />
                        <x-dropdown.item label="Edit Permissions" icon="lock" wire:click="open('permission', { role_id: {{ $role->id }} })"/>
                    </x-table.td>
                @endif
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->roles->links() !!}

    @livewire(lw('app.settings.system.user-drawer'), key('user-drawer'))
    @livewire(lw('app.settings.system.role-form-modal'), key('role-form'))
    @livewire(lw('app.settings.system.permission-form-modal'), key('permission-form'))
</div>