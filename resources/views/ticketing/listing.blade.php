<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Support Tickets">
        <x-button.create :href="route('ticketing.create')" label="New Ticket"/>
    </x-page-header>

    <x-table :total="$this->tickets->total()" :links="$this->tickets->links()">
        <x-slot:toolbar>
            <x-tab wire:model="filters.status">
                @foreach (['all', 'opened', 'closed'] as $item)
                    <x-tab.item :name="$item === 'all' ? null : $item" :label="str()->headline($item)"/>
                @endforeach
            </x-tab>
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="created_at" label="Date"/>
            <x-table.th sort="number" label="Number"/>
            <x-table.th sort="subject" label="Subject"/>
            <x-table.th class="text-right" label="Status"/>
            <x-table.th class="text-right" label="Created By"/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->tickets as $ticket)
                <x-table.tr>
                    <x-table.td :datetime="$ticket->created_at"/>
                    <x-table.td :href="route('ticketing.update', [$ticket->id])" :label="$ticket->number"/>
                    <x-table.td :href="route('ticketing.update', [$ticket->id])"
                        :label="str($ticket->subject)->limit(50)"
                        :small="str($ticket->description)->limit(80)"
                    />
                    <x-table.td :status="$ticket->status" class="text-right"/>
                    <x-table.td :label="str($ticket->created_by_user->name ?? '--')->limit(15)" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>