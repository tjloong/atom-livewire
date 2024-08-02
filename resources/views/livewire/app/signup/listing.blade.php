<x-table>
    <x-slot:header>
        <x-table.searchbar :total="$this->paginator->total()">
            <x-table.filters>
                <x-inputs>
                    <x-select wire:model="filters.status" options="enum.signup.status" multiple/>
                    <x-date-picker mode="range" wire:model="filters.created_at" label="app.label.created-date"/>
                </x-inputs>
            </x-table.filters>

            <x-table.export/>
        </x-table.searchbar>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="app.label.name"/>
        <x-table.th label="app.label.email"/>
        <x-table.th label="app.label.status" align="right"/>
        <x-table.th label="app.label.date" align="right"/>
    </x-slot:thead>

    @foreach ($this->paginator->items() as $row)
        <x-table.tr wire:click="$emit('editSignup', {{ Js::from($row->id) }})">
            <x-table.td :label="$row->user->name" class="font-medium"/>
            <x-table.td :label="$row->user->email"/>
            <x-table.td :status="[$row->status->badge()]" align="right"/>
            <x-table.td :date="$row->created_at" align="right"/>
        </x-table.tr>
    @endforeach
</x-table>
