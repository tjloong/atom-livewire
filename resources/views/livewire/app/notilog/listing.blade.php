<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum wire:model="filters.status" label="app.label.status" enum="notilog.status" multiple/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="app.label.date" sort="created_at"/>
            <x-table.th label="app.label.subject"/>
            <x-table.th label="app.label.to"/>
            <x-table.th label="app.label.status" align="right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('showNotilog', '{{ $row->ulid }}')">
                <x-table.td :checkbox="$row->id"/>
                <x-table.td :timestamp="$row->created_at"/>
                <x-table.td :label="$row->subject"/>
                <x-table.td :tags="$row->to"/>
                <x-table.td :badges="$row->status->badge()" align="right"/>
            </x-table.td>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>