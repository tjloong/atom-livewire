<x-table>
    <x-slot:header>
        <x-table.searchbar>
            <x-table.filters>
                <x-inputs>
                    <x-select wire:model="filters.type" label="app.label.type" options="enum.banner-type" multiple/>
                    <x-select wire:model="filters.placement" label="app.label.placement" options="enum.banner-placement" multiple/>
                    <x-select wire:model="filters.status" label="app.label.status" options="enum.banner-status" multiple/>
                </x-inputs>
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
        <x-table.tr wire:click="$emit('editBanner', {{ Js::from(['ulid' => $row->ulid]) }})">
            <x-table.td :label="$row->name" :image="optional($row->image)->url" class="font-medium"/>
            <x-table.td :label="$row->type->label()"/>
            <x-table.td :tags="collect($row->placement)->map(fn($val) => $val->label())"/>        
            <x-table.td :date="$row->start_at" align="right"/>
            <x-table.td :date="$row->end_at" align="right"/>
            <x-table.td :badges="[$row->status->badge()]" align="right"/>
        </x-table.td>
    @endforeach
</x-table>
