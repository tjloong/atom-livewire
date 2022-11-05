<x-table>
    <x-slot:header>
        <x-table.header label="Pages"/>
        <x-table.searchbar :total="$this->pages->count()"/>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th sort="name" label="Page"/>
        @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
        <x-table.th sort="title" label="Title"/>
    </x-slot:thead>

    @foreach ($this->pages as $page)
        <x-table.tr>
            <x-table.td :label="$page->name" :href="route('app.page.update', [$page])"/>
            @if (count(config('atom.locales')) > 1) <x-table.td :status="$page->locale"/> @endif
            <x-table.td :label="$page->title" :href="route('app.page.update', [$page])"/>
        </x-table.tr>
    @endforeach
</x-table>
