<div class="max-w-screen-xl mx-auto">
    @if ($fullpage)
        <x-page-header title="Support Tickets">
            <x-button icon="add" 
                label="New Ticket"
                :href="route('app.ticket.create')" 
            />
        </x-page-header>
    @endif

    <x-table :data="$this->tickets->items()">
        <x-slot:header>
            @if (!$fullpage)
                <x-table.header label="Support Tickets">
                    <x-button size="sm" icon="add"
                        label="New Ticket"
                        :href="route('app.ticket.create')" 
                    />
                </x-table.header>
            @endif

            <x-table.searchbar :total="$this->tickets->total()"/>

            <x-table.toolbar>
                <x-form.select :label="false"
                    wire:model="filters.status"
                    :options="collect(['pending', 'closed'])->map(fn($val) => [
                        'value' => $val, 'label' => ucfirst($val),
                    ])"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>
    </x-table>

    {!! $this->tickets->links() !!}
</div>