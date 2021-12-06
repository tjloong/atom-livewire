<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Banners">
        <x-button icon="plus" href="{{ route('banner.create', ['back' => url()->current()]) }}">
            New Banner
        </x-button>
    </x-page-header>

    <x-table :total="$banners->total()" :links="$banners->links()">
        <x-slot name="head">
            <x-table.head sort="name">Name</x-table.head>
            <x-table.head>Status</x-table.head>
            <x-table.head sort="start_at" align="right">Start</x-table.head>
            <x-table.head sort="end_at" align="right">End</x-table.head>
        </x-slot>

        <x-slot name="body">
        @foreach ($banners as $banner)
            <x-table.row>
                <x-table.cell>
                    <a href="{{ route('banner.update', [$banner]) }}">
                        {{ $banner->name }}
                    </a>
                </x-table.cell>
                <x-table.cell>
                    <x-badge>{{ $banner->status }}</x-badge>
                </x-table.cell>
                <x-table.cell class="text-right">{{ format_date($banner->start_at) }}</x-table.cell>
                <x-table.cell class="text-right">{{ format_date($banner->end_at) }}</x-table.cell>
            </x-table.row>
        @endforeach
        </x-slot>
    </x-table>
</div>