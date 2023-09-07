<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.export/>
            </x-table.searchbar>

            <x-table.toolbar>
                <x-form.select.enum :label="false"
                    wire:model="filters.status"
                    enum="signup.status"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name"/>
            <x-table.th label="Email"/>
            <x-table.th label="Status" class="text-right"/>
            <x-table.th label="Date" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr>
                <x-table.td :label="$row->user->name" wire:click="$emit('updateSignup', {{ $row->id }})"/>
                <x-table.td :label="$row->user->email"/>
                <x-table.td :status="$row->status->badge()" class="text-right"/>
                <x-table.td :date="$row->created_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>