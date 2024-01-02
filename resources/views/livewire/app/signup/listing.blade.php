<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="app.label.status" enum="signup.status" multiple
                            wire:model="filters.status"/>
                        <x-form.date range label="app.label.created-date" wire:model="filters.created_at"/>
                    </x-form.group>
                </x-table.filters>

                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.name"/>
            <x-table.th label="app.label.email"/>
            <x-table.th label="app.label.status" class="text-right"/>
            <x-table.th label="app.label.date" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('updateSignup', {{ $row->id }})">
                <x-table.td :label="$row->user->name" class="font-medium"/>
                <x-table.td :label="$row->user->email"/>
                <x-table.td :status="$row->status->badge()" class="text-right"/>
                <x-table.td :date="$row->created_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>