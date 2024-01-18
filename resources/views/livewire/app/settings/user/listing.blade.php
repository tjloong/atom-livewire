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
            <x-table.th label="app.label.name" sort="name"/>
            @if ($this->isLoginMethod('username')) <x-table.th label="app.label.username"/> @endif
            @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.th label="app.label.email"/> @endif
            @if (has_table('roles')) <x-table.th label="app.label.role"/> @endif
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $user)
            <x-table.tr wire:click="$emit('updateUser', {{ $user->id }})">
                <x-table.td :label="$user->name" class="font-medium"/>
                @if ($this->isLoginMethod('username')) <x-table.td :label="$user->username ?? '--'"/> @endif
                @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.td :label="$user->email ?? '--'"/> @endif
                @if (has_table('roles')) <x-table.td :label="$user->role->name ?? '--'"/> @endif
                <x-table.td :status="$user->status->badge()"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>