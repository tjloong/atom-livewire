<div class="max-w-screen-md">
    <x-heading title="Users">
        <x-button icon="add" 
            label="New User" 
            wire:click="$emit('createUser')"/>
    </x-heading>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>

            <x-table.toolbar>
                <div class="flex items-center gap-2">
                    <x-form.select.enum wire:model="filters.status" :label="false"
                        placeholder="All Status" 
                        enum="user.status"
                    />

                    <x-form.select.role wire:model="filters.role_id" :label="false"
                        placeholder="All Roles"
                    />

                    <x-form.select.team wire:model="filters.team_id" :label="false"
                        placeholder="All Teams"
                    />
                </div>

                <x-table.trashed/>
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            @if ($this->isLoginMethod('username')) <x-table.th label="Username"/> @endif
            @if ($this->isLoginMethod('email')) <x-table.th label="Email"/> @endif
            @if (has_table('roles')) <x-table.th label="Role"/> @endif
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $user)
            <x-table.tr>
                <x-table.td :label="$user->name" wire:click="$emit('updateUser', {{ $user->id }})"/>
                @if ($this->isLoginMethod('username')) <x-table.td :label="$user->username ?? '--'"/> @endif
                @if ($this->isLoginMethod('email')) <x-table.td :label="$user->email ?? '--'"/> @endif
                @if (has_table('roles')) <x-table.td :label="$user->role->name ?? '--'"/> @endif
                <x-table.td :status="[$user->status->color() => $user->status->value]"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-noresult title="No Users" subtitle="User list is empty"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}

    @livewire('app.settings.user.update', key('user-update'))
</div>