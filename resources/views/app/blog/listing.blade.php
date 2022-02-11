<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Blogs">
        <x-button icon="plus" href="{{ route('blog.create', ['back' => url()->current()]) }}">
            New Blog
        </x-button>
    </x-page-header>

    <x-table :total="$blogs->total()" :links="$blogs->links()">
        <x-slot name="toolbar">
            <x-tabs wire:model="filterStatus">
                <x-tabs item>All</x-tabs>
                <x-tabs item>Published</x-tabs>
                <x-tabs item>Draft</x-tabs>
            </x-tabs>
        </x-slot>

        <x-slot name="head">
            <x-table head sort="title">Title</x-table>
            <x-table head>Status</x-table>
            <x-table head>Category</x-table>
            <x-table head sort="updated_at" align="right">Updated At</x-table>
            <x-table head sort="created_at" align="right">Created At</x-table>
        </x-slot>

        <x-slot name="body">
        @foreach ($blogs as $blog)
            <x-table row>
                <x-table cell class="max-w-xs">
                    <div class="grid">
                        <a href="{{ route('blog.update', [$blog->id]) }}" class="truncate">
                            {{ $blog->title }}
                        </a>
                    </div>
                </x-table>
                <x-table cell>
                    <x-badge>{{ $blog->status }}</x-badge>
                </x-table>
                <x-table cell>{{ $blog->category->name ?? '--' }}</x-table>
                <x-table cell class="text-right">{{ format_date($blog->updated_at, 'human') }}</x-table>
                <x-table cell class="text-right">{{ format_date($blog->created_at, 'human') }}</x-table>
            </x-table>
        @endforeach
        </x-slot>
    </x-table>
</div>