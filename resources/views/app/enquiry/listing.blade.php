<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Enquiries"/>

    <x-table :total="$this->enquiries->total()" :links="$this->enquiries->links()" export>
        <x-slot:head>
            <x-table.th sort="created_at">Date</x-table.th>
            <x-table.th sort="name">Name</x-table.th>
            <x-table.th sort="phone">Phone</x-table.th>
            <x-table.th sort="email">Email</x-table.th>
            <x-table.th class="text-right">Status</x-table.th>
        </x-slot:head>

        <x-slot:body>
        @foreach ($this->enquiries as $enquiry)
            <x-table.tr>
                <x-table.td>
                    {{ format_date($enquiry->created_at) }}
                    <div class="text-gray-500">
                        {{ format_date($enquiry->created_at, 'time') }}
                    </div>
                </x-table.td>

                <x-table.td :label="$enquiry->name" :href="route('app.enquiry.update', [$enquiry->id])"/>
                <x-table.td :label="$enquiry->phone"/>
                <x-table.td :label="$enquiry->email"/>
                <x-table.td :status="$enquiry->status" class="text-right"/>
            </x-table.tr>
        @endforeach
        </x-slot:body>
    </x-table>
</div>