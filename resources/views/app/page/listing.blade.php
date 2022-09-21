<x-table header="Pages" :total="$this->pages->count()">
    <x-slot:head>
        <x-table.th sort="name" label="Page"/>
        @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
        <x-table.th sort="title" label="Title"/>
        <x-table.th sort="updated_at" class="text-right" label="Last Update"/>
    </x-slot:head>

    <x-slot:body>
    @foreach ($this->pages as $page)
        <x-table.tr>
            <x-table.td :label="$page->name" :href="route('app.page.update', [$page])"/>
            @if (count(config('atom.locales')) > 1) <x-table.td :status="$page->locale"/> @endif
            <x-table.td :label="$page->title" :href="route('app.page.update', [$page])"/>
            <x-table.td :date="$page->updated_at" class="text-right"/>
        </x-table.tr>
    @endforeach
    </x-slot:body>
</x-table>
