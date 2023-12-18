<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    
        <x-slot:thead>
            <x-table.th label="app.label.page" sort="name"/>
            @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
            <x-table.th label="app.label.title" sort="title"/>
        </x-slot:thead>
    
        @foreach ($this->paginator->items() as $page)
            <x-table.tr wire:click="$emit('updatePage', {{ $page->id }})">
                <x-table.td :label="$page->name" class="font-medium"/>
                @if (count(config('atom.locales')) > 1) <x-table.td :status="$page->locale"/> @endif
                <x-table.td :label="$page->title"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>
