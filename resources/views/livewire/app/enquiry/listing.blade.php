<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="common.label.status" enum="enquiry.status"
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>

                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="common.label.date" sort="created_at"/>
            <x-table.th label="common.label.name" sort="name"/>
            <x-table.th label="common.label.phone"/>
            <x-table.th label="common.label.email"/>
            <x-table.th label="common.label.status"/>
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
                title="enquiry.empty.title"
                subtitle="enquiry.empty.subtitle"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>