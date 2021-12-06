<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Enquiries"/>

    <x-table :total="$enquiries->total()" :links="$enquiries->links()">
        <x-slot name="head">
            <x-table.head sort="created_at">Date</x-table.head>
            <x-table.head sort="name">Name</x-table.head>
            <x-table.head sort="phone">Phone</x-table.head>
            <x-table.head sort="email">Email</x-table.head>
            <x-table.head align="right">Status</x-table.head>
        </x-slot>

        <x-slot name="body">
        @foreach ($enquiries as $enquiry)
            <x-table.row>
                <x-table.cell>
                    {{ format_date($enquiry->created_at) }}
                    <div class="text-xs text-gray-500">
                        {{ format_date($enquiry->created_at, 'time') }}
                    </div>
                </x-table.cell>
                <x-table.cell>
                    <a href="{{ route('enquiry.update', [$enquiry]) }}">
                        {{ $enquiry->name }}
                    </a>
                </x-table.cell>
                <x-table.cell>{{ $enquiry->phone }}</x-table.cell>
                <x-table.cell>{{ $enquiry->email }}</x-table.cell>
                <x-table.cell class="text-right">
                    <x-badge>{{ $enquiry->status }}</x-badge>
                </x-table.cell>
            </x-table.row>
        @endforeach
        </x-slot>
    </x-table>
</div>