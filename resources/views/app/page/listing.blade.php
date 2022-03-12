<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Pages"/>

    <x-table :total="$pages->total()" :links="$pages->links()">
        <x-slot name="head">
            <x-table head sort="name">Page</x-table>
            <x-table head sort="title">Title</x-table>
            <x-table head sort="updated_at" align="right">Updated At</x-table>
        </x-slot>

        <x-slot name="body">
        @foreach ($pages as $page)
            <x-table row>
                <x-table cell>
                    <a href="{{ route('app.page.update', [$page]) }}">
                        {{ $page->name }}
                    </a>
                </x-table>
                <x-table cell>{{ $page->title }}</x-table>
                <x-table cell class="text-right">{{ format_date($page->updated_at, 'human') }}</x-table>
            </x-table>
        @endforeach
        </x-slot>
    </x-table>
</div>