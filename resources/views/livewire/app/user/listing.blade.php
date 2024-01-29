<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum enum="user.status" wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.name" sort="name"/>
            @if ($this->isLoginMethod('username')) <x-table.th label="app.label.username"/> @endif
            @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.th label="app.label.email"/> @endif
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $user)
            <x-table.tr wire:click="$emit('updateUser', {{ $user->id }})">
                <x-table.td :label="$user->name" class="font-medium"/>
                @if ($this->isLoginMethod('username')) <x-table.td :label="$user->username ?? '--'"/> @endif
                @if ($this->isLoginMethod(['email', 'email-verified'])) <x-table.td :label="$user->email ?? '--'"/> @endif
                <x-table.td :status="$user->status->badge()"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>