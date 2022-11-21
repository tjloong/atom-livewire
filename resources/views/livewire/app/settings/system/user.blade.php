<div class="max-w-screen-lg mx-auto">
    <x-table>
        <x-slot:header>
            <x-table.header label="Users">
                <x-button size="sm" color="gray"
                    label="New User" 
                    wire:click="open('create')"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->users->total()"/>

            <x-table.toolbar>
                <x-form.select 
                    wire:model="filters.status"
                    :options="collect(['active', 'inactive', 'blocked', 'trashed'])
                        ->map(fn($val) => ['value' => $val, 'label' => ucfirst($val)])"
                    placeholder="All Status"
                />

                <x-table.trashed :count="$this->trashed"/>
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            <x-table.th/>
            @module('roles') <x-table.th label="Role" class="text-right"/> @endmodule
            <x-table.th class="w-4"/>
        </x-slot:thead>

        @foreach ($this->users as $user)
            <x-table.tr>
                @if ($user->id === auth()->id()) <x-table.td :label="$user->name.'('.__('You').')'"/>
                @else <x-table.td :label="$user->name" :small="$user->email" wire:click="open('edit', {{ $user->id }})"/>
                @endif

                <x-table.td :status="$user->status" class="text-right"/>

                @module('roles')
                    <x-table.td :label="data_get($user->role, 'name')" class="text-right"/>
                @endmodule

                @if ($user->id !== auth()->id())
                    <x-table.td dropdown>
                        <x-dropdown.item label="Edit" icon="edit" wire:click="open('edit', {{ $user->id }})"/>

                        @if ($user->status === 'trashed')
                            <x-dropdown.item label="Restore" icon="restore" wire:click="restore({{ $user->id }})"/>
                            <x-dropdown.item label="Force Delete" icon="delete" x-on:click="$dispatch('confirm', {
                                title: '{{ __('Force Delete User') }}',
                                message: '{{ __('This will permanently delete the user. Are you sure?') }}',
                                type: 'error',
                                onConfirmed: () => $wire.delete({{ $user->id }}, true),
                            })"/>
                        @else
                            @if ($user->status === 'blocked')
                                <x-dropdown.item label="Unblock" icon="unblock" x-on:click="$dispatch('confirm', {
                                    title: '{{ __('Unblock User') }}',
                                    message: '{{ __('This will unblock the user. Are you sure?') }}',
                                    type: 'error',
                                    onConfirmed: () => $wire.unblock({{ $user->id }}),
                                })"/>
                            @else
                                <x-dropdown.item label="Block" icon="block" x-on:click="$dispatch('confirm', {
                                    title: '{{ __('Block User') }}',
                                    message: '{{ __('This will block the user. Are you sure?') }}',
                                    type: 'error',
                                    onConfirmed: () => $wire.block({{ $user->id }}),
                                })"/>
                            @endif

                            <x-dropdown.item label="Delete" icon="delete" x-on:click="$dispatch('confirm', {
                                title: '{{ __('Delete User') }}',
                                message: '{{ __('This will delete the user. Are you sure?') }}',
                                type: 'error',
                                onConfirmed: () => $wire.delete({{ $user->id }}),
                            })"/>
                        @endif
        
                        @if (enabled_module('permissions') && count(config('atom.app.permissions.'.auth()->user()->account->type)))
                            <x-dropdown.item label="Edit Permissions" icon="lock" wire:click="open('permission', { user_id: {{ $user->id }} })"/>
                        @endif
                    </x-table.td>
                @endif
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-empty-state title="No Users" subtitle="User list is empty"/>
        </x-slot:empty>
    </x-table>

    {!! $this->users->links() !!}

    @livewire(lw('app.settings.system.user-form-modal'), key('user-form'))
    @livewire(lw('app.settings.system.permission-form-modal'), key('permission-form'))
</div>