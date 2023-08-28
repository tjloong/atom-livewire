<div
    x-data="{ enquiryId: @js($enquiryId) }"
    x-init="enquiryId && $wire.emit('updateEnquiry', enquiryId)"
    class="max-w-screen-xl mx-auto">
    <x-heading title="Enquiries" 2xl/>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.export/>
            </x-table.searchbar>

            <x-table.toolbar>
                <x-form.select.enum :label="false"
                    wire:model="filters.status" 
                    enum="enquiry.status"
                    placeholder="All Status"/>
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Date" sort="created_at"/>
            <x-table.th label="Name" sort="name"/>
            <x-table.th label="Phone"/>
            <x-table.th label="Email"/>
            <x-table.th label="Status"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $enquiry)
            <x-table.tr>
                <x-table.td :date="$enquiry->created_at"/>
                <x-table.td :label="$enquiry->name" wire:click="$emit('updateEnquiry', {{ $enquiry->id }})"/>
                <x-table.td :label="$enquiry->phone"/>
                <x-table.td :label="$enquiry->email"/>
                <x-table.td :status="[$enquiry->status->color() => $enquiry->status->value]"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}

    @livewire('app.enquiry.update', key(uniqid()))
</div>