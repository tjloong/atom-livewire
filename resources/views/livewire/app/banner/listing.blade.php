<div>
    <x-table wire:sorted="sort">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-group>
                        <x-form.select.enum wire:model="filters.type" label="app.label.type" enum="banner.type" multiple/>
                        <x-form.select.enum wire:model="filters.placement" label="app.label.placement" enum="banner.placement" multiple/>
                        <x-form.select.enum wire:model="filters.status" label="app.label.status" enum="banner.status" multiple/>
                    </x-group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.banner" sort="name"/>
            <x-table.th label="Type" sort="type"/>
            <x-table.th label="Placement"/>
            <x-table.th label="app.label.start-date" sort="start_at" align="right"/>
            <x-table.th label="app.label.end-date" sort="end_at" align="right"/>
            <x-table.th label="app.label.status" align="right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr data-sortable-id="{{ $row->id }}" wire:click="$emit('updateBanner', {{ $row->id }})">
                <x-table.td :label="$row->name" :image="optional($row->image)->url" class="font-medium"/>
                <x-table.td :label="$row->type->label()"/>
                <x-table.td :tags="collect($row->placement)->map(fn($val) => $val->label())"/>        
                <x-table.td :date="$row->start_at" align="right"/>
                <x-table.td :date="$row->end_at" align="right"/>
                <x-table.td :badges="$row->status->badge()" align="right"/>
            </x-table.td>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>