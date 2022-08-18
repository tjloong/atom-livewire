<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Pages"/>

    <x-table :total="$pages->total()" :links="$pages->links()">
        <x-slot:head>
            <x-table.th sort="name" label="Page"/>
            @if (count(config('atom.locales')) > 1) <x-table.th class="text-right"/> @endif
            <x-table.th sort="title" label="Title"/>
            <x-table.th sort="updated_at" class="text-right" label="Last Update"/>
        </x-slot:head>

        <x-slot:body>
        @foreach ($pages as $page)
            <x-table.tr>
                <x-table.td :label="$page->name" :href="route('app.page.update', [$page])"/>
                @if (count(config('atom.locales')) > 1)
                    <x-table.td>
                        <x-badge :label="$page->locale" class="uppercase"/>
                    </x-table.td>
                @endif
                <x-table.td :label="$page->title"/>
                <x-table.td :date="$page->updated_at" class="text-right"/>
            </x-table.tr>
        @endforeach
        </x-slot:body>
    </x-table>
</div>