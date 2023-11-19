<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="common.label.status" enum="popup.status" multiple
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="common.label.name" sort="name"/>
            <x-table.th label="common.label.status" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('updatePopup', {{ $row->id }})">
                <x-table.td :label="$row->name" class="font-medium"/>
                <x-table.td :status="$row->status->badge()" class="text-right"/>
            </x-table.td>
        @endforeach

        <x-slot:empty>
            <x-no-result
                title="popup.empty.title"
                subtitle="popup.empty.subtitle"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>