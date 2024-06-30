<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.timestamp" sort="created_at"/>
            <x-table.th label="app.label.user"/>
            <x-table.th label="app.label.event"/>
            <x-table.th label="app.label.type"/>
            <x-table.th label="app.label.tag"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('showAudit', { id: {{ $row->id }} })">
                <x-table.td :timestamp="$row->created_at"/>
                <x-table.td :label="optional($row->user)->name"/>
                <x-table.td :badges="$row->event->badge()"/>
                <x-table.td :label="str($row->auditable_type)->headline()"/>
                <x-table.td :tags="$row->tags"/>
            </x-table.td>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>