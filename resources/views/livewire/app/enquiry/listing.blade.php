<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="atom::enquiry.label.status" enum="enquiry.status"
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>

                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="atom::enquiry.label.date" sort="created_at"/>
            <x-table.th label="atom::enquiry.label.name" sort="name"/>
            <x-table.th label="atom::enquiry.label.phone"/>
            <x-table.th label="atom::enquiry.label.email"/>
            <x-table.th label="atom::enquiry.label.status"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $enquiry)
            <x-table.tr>
                <x-table.td :date="$enquiry->created_at"/>
                <x-table.td :label="$enquiry->name" 
                    wire:click="$emit('updateEnquiry', {{ $enquiry->id }})"/>
                <x-table.td :label="$enquiry->phone"/>
                <x-table.td :label="$enquiry->email"/>
                <x-table.td :status="$enquiry->status->badge()"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-no-result
                title="atom::enquiry.empty.title"
                subtitle="atom::enquiry.empty.subtitle"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>