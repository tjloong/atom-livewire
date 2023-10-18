<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="atom::common.label.status" enum="signup.status"
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>

                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="atom::common.label.name"/>
            <x-table.th label="atom::common.label.email"/>
            <x-table.th label="atom::common.label.status" class="text-right"/>
            <x-table.th label="atom::common.label.date" class="text-right"/>
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