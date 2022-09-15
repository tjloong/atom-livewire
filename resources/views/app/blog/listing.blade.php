<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Blogs">
        <x-button label="New Blog" href="{{ route('app.blog.create') }}"/>
    </x-page-header>

    <x-table :total="$this->blogs->total()" :links="$this->blogs->links()">
        <x-slot:toolbar>
            <x-tab wire:model="filters.status">
                @foreach (['all', 'published', 'draft'] as $item)
                    <x-tab.item :name="$item === 'all' ? null : $item" :label="str()->headline($item)"/>
                @endforeach
            </x-tab>
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="title">Title</x-table.th>
            <x-table.th>Status</x-table.th>
            <x-table.th sort="updated_at" class="text-right">Updated At</x-table.th>
            <x-table.th sort="created_at" class="text-right">Created At</x-table.th>
        </x-slot:head>

        <x-slot:body>
        @foreach ($this->blogs as $blog)
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