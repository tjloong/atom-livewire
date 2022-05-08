<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Blogs">
        <x-button.create label="New Blog" href="{{ route('app.blog.create') }}"/>
    </x-page-header>

    <x-table :total="$blogs->total()" :links="$blogs->links()">
        <x-slot:toolbar>
            <x-tabs wire:model="filters.status">
                <x-tabs item>All</x-tabs>
                <x-tabs item>Published</x-tabs>
                <x-tabs item>Draft</x-tabs>
            </x-tabs>
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="title">Title</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th sort="updated_at" class="text-right">Updated At</x-table.th>
            <x-table.th sort="created_at" class="text-right">Created At</x-table.th>
        </x-slot:head>

        <x-slot:body>
        @foreach ($blogs as $blog)
            <x-table.tr>
                <x-table.td class="max-w-xs">
                    <div class="grid">
                        <a href="{{ route('app.blog.update', [$blog->id]) }}" class="truncate">
                            {{ $blog->title }}
                        </a>
                    </div>
                </x-table.td>
                <x-table.td>
                    <x-badge>{{ $blog->status }}</x-badge>
                </x-table.td>
                <x-table.td class="text-right">{{ format_date($blog->updated_at, 'human') }}</x-table.td>
                <x-table.td class="text-right">{{ format_date($blog->created_at, 'human') }}</x-table.td>
            </x-table.tr>
        @endforeach
        </x-slot:body>
    </x-table>
</div>