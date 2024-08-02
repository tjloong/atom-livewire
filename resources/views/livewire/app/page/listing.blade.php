<x-table>
    <x-slot:header>
        <x-table.searchbar :total="$this->paginator->total()"/>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="app.label.page" sort="name"/>
        <x-table.th label="app.label.title" sort="title"/>
    </x-slot:thead>

    @foreach ($this->paginator->items() as $row)
        <x-table.tr wire:click="$emit('editPage', {{ Js::from($row->id) }})">
            <x-table.td :label="$row->name" :badges="$row->locale" class="font-medium"/>
            <x-table.td :label="$row->title"/>
        </x-table.tr>
    @endforeach
</x-table>
