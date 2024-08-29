<x-table>
    <x-slot:header>
        <x-table.searchbar :total="$this->paginator->total()">
            <x-table.filters>
                <x-inputs>
                    <x-select wire:model="filters.status" options="enum.user-status"/>
                </x-inputs>
            </x-table.filters>
        </x-table.searchbar>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="app.label.name" sort="name"/>
        <x-table.th label="app.label.email"/>
        <x-table.th/>
    </x-slot:thead>

    @foreach ($this->paginator->items() as $row)
        <x-table.tr wire:click="$emit('editUser', {{ Js::from(['id' => $row->id]) }})">
            <x-table.td :label="$row->name" class="font-medium"/>
            <x-table.td :label="$row->email"/>
            <x-table.td :badges="[$row->status->badge()]" align="right"/>
        </x-table.tr>
    @endforeach
</x-table>
