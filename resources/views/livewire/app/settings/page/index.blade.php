<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Pages"/>
    
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->pages->count()"/>
        </x-slot:header>
    
        <x-slot:thead>
            <x-table.th sort="name" label="Page"/>
            @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
            <x-table.th sort="title" label="Title"/>
        </x-slot:thead>
    
        @foreach ($this->pages as $page)
            <x-table.tr>
                <x-table.td :label="$page->name" wire:click="update({{ $page->id }})"/>
                @if (count(config('atom.locales')) > 1) <x-table.td :status="$page->locale"/> @endif
                <x-table.td :label="$page->title"/>
            </x-table.tr>
        @endforeach
    </x-table>

    @livewire('app.settings.page.update', key(uniqid()))
</div>
