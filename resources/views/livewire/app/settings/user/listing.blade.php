<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum enum="user.status" wire:model="filters.status"/>
                        @if (has_table('roles')) <x-form.select.role wire:model="filters.role_id"/> @endif
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            @if ($this->isLoginMethod('username')) <x-table.th label="Username"/> @endif
            @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.th label="Email"/> @endif
            @if (has_table('roles')) <x-table.th label="Role"/> @endif
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $user)
            <x-table.tr>
                <x-table.td :label="$user->name" wire:click="$emit('updateUser', {{ $user->id }})"/>
                @if ($this->isLoginMethod('username')) <x-table.td :label="$user->username ?? '--'"/> @endif
                @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.td :label="$user->email ?? '--'"/> @endif
                @if (has_table('roles')) <x-table.td :label="$user->role->name ?? '--'"/> @endif
                <x-table.td :status="$user->status->badge()"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-no-result 
                title="user.empty.title"
                subtitle="user.empty.subtitle"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>