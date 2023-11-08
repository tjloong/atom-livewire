<div>
    <x-table wire:sorted="sort">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="common.label.status" enum="banner.status"
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th/>
            <x-table.th label="banner.label.name" sort="name"/>
            <x-table.th label="banner.label.type" sort="type"/>
            <x-table.th label="banner.label.start-date" sort="start_at"/>
            <x-table.th label="banner.label.end-date" sort="end_at"/>
            <x-table.th label="banner.label.status"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $banner)
            <x-table.tr data-sortable-id="{{ $banner->id }}"
                class="cursor-pointer"
                wire:click="$emit('updateBanner', {{ $banner->id }})">
                <x-table.td :image="optional($banner->image)->url"/>
                <x-table.td :label="$banner->name" class="font-medium"/>
                <x-table.td :label="$banner->type"/>
                <x-table.td :date="$banner->start_at"/>
                <x-table.td :date="$banner->end_at"/>
                <x-table.td :status="$banner->status->badge()"/>
            </x-table.td>
        @endforeach

        <x-slot:empty>
            <x-no-result
                title="banner.empty.title"
                subtitle="banner.empty.subtitle"/>
        </x-slot:empty>
    </x-table>

    {!! $this->paginator->links() !!}
</div>