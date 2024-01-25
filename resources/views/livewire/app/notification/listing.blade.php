<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum wire:model="filters.status" label="app.label.status" enum="notification.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="app.label.date" sort="created_at"/>
            @if ($channel === 'mail')
                <x-table.th label="app.label.notification-subject"/>
                <x-table.th label="app.label.status" class="text-right"/>
            @endif
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('showNotification', '{{ $row->ulid }}')">
                <x-table.td :checkbox="$row->id"/>
                <x-table.td :timestamp="$row->created_at"/>
                @if ($channel === 'mail')
                    <x-table.td :label="$row->subject"/>
                    <x-table.td :status="$row->status->badge()" class="text-right"/>
                @endif
            </x-table.td>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>