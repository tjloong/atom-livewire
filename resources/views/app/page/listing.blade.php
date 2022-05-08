<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Pages"/>

    <x-table :total="$pages->total()" :links="$pages->links()">
        <x-slot:head>
            <x-table.th sort="name">Page</x-table.th>
            <x-table.th sort="title">Title</x-table.th>
            <x-table.th sort="updated_at" class="text-right">Updated At</x-table.th>
        </x-slot:head>

        <x-slot:body>
        @foreach ($pages as $page)
            <x-table.tr>
                <x-table.td>
                    <a href="{{ route('app.page.update', [$page]) }}">
                        {{ $page->name }}
                    </a>
                </x-table.td>

                <x-table.td>{{ $page->title }}</x-table.td>
                <x-table.td class="text-right">{{ format_date($page->updated_at, 'human') }}</x-table.td>
            </x-table.tr>
        @endforeach
        </x-slot:body>
    </x-table>
</div>