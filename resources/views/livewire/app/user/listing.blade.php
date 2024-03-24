<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-group>
                        <x-form.select.enum enum="user.status" wire:model="filters.status"/>
                    </x-group>
                </x-table.filters>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.name" sort="name"/>
            <x-table.th label="app.label.email"/>
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $user)
            <x-table.tr wire:click="$emit('updateUser', {{ $user->id }})">
                <x-table.td :label="$user->name" class="font-medium"/>
                <x-table.td :label="$user->email"/>
                <x-table.td :badges="$user->status->badge()" align="right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>