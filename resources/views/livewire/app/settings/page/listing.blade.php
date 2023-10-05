<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    
        <x-slot:thead>
            <x-table.th label="atom::page.label.name" sort="name"/>
            @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
            <x-table.th label="atom::page.label.title" sort="title"/>
        </x-slot:thead>
    
        @foreach ($this->paginator->items() as $page)
            <x-table.tr>
                <x-table.td :label="$page->name" 
                    wire:click="$emit('updatePage', {{ $page->id }})"/>
                @if (count(config('atom.locales')) > 1) <x-table.td :status="$page->locale"/> @endif
                <x-table.td :label="$page->title"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>
