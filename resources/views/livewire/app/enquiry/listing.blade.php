<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Enquiries"/>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->enquiries->total()">
                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Date" sort="created_at"/>
            <x-table.th label="Name" sort="name"/>
            <x-table.th label="Phone" sort="phone"/>
            <x-table.th label="Email" sort="email"/>
            <x-table.th label="Status" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->enquiries as $enquiry)
            <x-table.tr>
                <x-table.td :datetime="$enquiry->created_at"/>
                <x-table.td :label="$enquiry->name" :href="route('app.enquiry.update', [$enquiry->id])"/>
                <x-table.td :label="$enquiry->phone"/>
                <x-table.td :label="$enquiry->email"/>
                <x-table.td :status="$enquiry->status" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->enquiries->links() !!}
</div>