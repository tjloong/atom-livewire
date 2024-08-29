<x-table>
    <x-slot:header>
        <x-table.searchbar :total="$this->paginator->total()">
            <x-table.filters>
                <x-inputs>
                    <x-select wire:model="filters.status" label="app.label.status" options="enum.popup-status" multiple/>
                </x-inputs>
            </x-table.filters>
        </x-table.searchbar>

        <x-table.checkbox-actions delete/>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="app.label.name" sort="name"/>
        <x-table.th label="app.label.status" align="right"/>
    </x-slot:thead>

    @foreach ($this->paginator->items() as $row)
        <x-table.tr wire:click="$emit('editPopup', {{ Js::from(['id' => $row->id]) }})">
            <x-table.td :label="$row->name" limit="100" class="font-medium"/>
            <x-table.td :status="[$row->status->badge()]" align="right"/>
        </x-table.td>
    @endforeach
</x-table>
