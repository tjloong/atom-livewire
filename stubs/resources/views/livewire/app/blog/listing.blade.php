<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Blogs">
        <x-button icon="plus" href="{{ route('blog.create', ['back' => url()->current()]) }}">
            New Blog
        </x-button>
    </x-page-header>

    <x-table :total="$blogs->total()" :links="$blogs->links()">
        <x-slot name="toolbar">
            <x-tabs wire:model="filterStatus">
                <x-tabs.tab>All</x-tabs.tab>
                <x-tabs.tab>Published</x-tabs.tab>
                <x-tabs.tab>Draft</x-tabs.tab>
            </x-tabs>
        </x-slot>

        <x-slot name="head">
            <x-table.head sort="title">Title</x-table.head>
            <x-table.head>Status</x-table.head>
            <x-table.head>Category</x-table.head>
            <x-table.head sort="updated_at" align="right">Updated At</x-table.head>
            <x-table.head sort="created_at" align="right">Created At</x-table.head>
        </x-slot>

        <x-slot name="body">
        @foreach ($blogs as $blog)
            <x-table.row>
                <x-table.cell>
                    <a href="{{ route('blog.update', [$blog->id]) }}">
                        {{ $blog->title }}
                    </a>
                </x-table.cell>
                <x-table.cell>
                    <x-badge>{{ $blog->status }}</x-badge>
                </x-table.cell>
                <x-table.cell>{{ $blog->category->name ?? '--' }}</x-table.cell>
                <x-table.cell class="text-right">{{ format_date($blog->updated_at, 'human') }}</x-table.cell>
                <x-table.cell class="text-right">{{ format_date($blog->created_at, 'human') }}</x-table.cell>
            </x-table.row>
        @endforeach
        </x-slot>
    </x-table>
</div>