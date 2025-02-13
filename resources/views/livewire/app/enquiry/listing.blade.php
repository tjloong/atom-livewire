<x-table>
    <x-slot:header>
        <x-table.searchbar>
            <x-table.filters>
                <x-inputs>
                    <x-select wire:model="filters.status" label="app.label.status" options="enum.enquiry-status" multiple/>
                    <x-date-picker mode="range" wire:model="filters.created_at" label="app.label.created-date"/>
                </x-inputs>
            </x-table.filters>

            <x-table.export/>
        </x-table.searchbar>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="app.label.date" sort="created_at"/>
        <x-table.th label="app.label.name" sort="name"/>
        <x-table.th label="app.label.phone"/>
        <x-table.th label="app.label.email"/>
        <x-table.th label="app.label.status" align="right"/>
    </x-slot:thead>

    @foreach ($this->paginator->items() as $row)
        <x-table.tr wire:click="$emit('editEnquiry', {{ $row->ulid }})">
            <x-table.td :date="$row->created_at"/>
            <x-table.td :label="$row->name" class="font-medium"/>
            <x-table.td :label="$row->phone"/>
            <x-table.td :label="$row->email"/>
            <x-table.td :badges="[$row->status->badge()]" align="right"/>
        </x-table.tr>
    @endforeach
</x-table>
