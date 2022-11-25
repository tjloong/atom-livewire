<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Support Tickets">
        <x-button :href="route('app.ticketing.create')" label="New Ticket"/>
    </x-page-header>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->tickets->total()"/>

            <x-table.toolbar>
                <x-form.select
                    wire:model="filters.status"
                    :options="collect(['pending', 'closed'])->map(fn($val) => [
                        'value' => $val, 'label' => ucfirst($val),
                    ])"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>
        
        <x-slot:thead>
            <x-table.th sort="created_at" label="Date"/>
            <x-table.th sort="number" label="Number"/>
            <x-table.th sort="subject" label="Subject"/>
            <x-table.th/>
            <x-table.th class="text-right" label="Status"/>
            <x-table.th class="text-right" label="Created By"/>
        </x-slot:thead>

        @foreach ($this->tickets as $ticket)
            <x-table.tr>
                <x-table.td :datetime="$ticket->created_at"/>
                <x-table.td :href="route('app.ticketing.update', [$ticket->id])" :label="$ticket->number"/>
                <x-table.td :href="route('app.ticketing.update', [$ticket->id])"
                    :label="str($ticket->subject)->limit(50)"
                    :small="str($ticket->description)->limit(80)"
                />
                <x-table.td>
                    <x-badge :label="model('ticket_comment')->getUnreadCount($ticket->id)"/>
                </x-table.td>
                <x-table.td :status="$ticket->status" class="text-right"/>
                <x-table.td :label="str($ticket->created_by_user->name ?? '--')->limit(15)" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->tickets->links() !!}
</div>