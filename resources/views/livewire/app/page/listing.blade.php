<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    
        <x-slot:thead>
            <x-table.th label="app.label.page" sort="name"/>
            <x-table.th label="app.label.title" sort="title"/>
        </x-slot:thead>
    
        @foreach ($this->paginator->items() as $page)
            <x-table.tr wire:click="$emit('updatePage', {{ $page->id }})">
                <x-table.td :label="$page->name" :badges="$page->locale" class="font-medium"/>
                <x-table.td :label="$page->title"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>
