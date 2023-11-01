<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="common.label.status" enum="blog.status"
                            wire:model="filters.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="common.label.title" sort="name"/>
            <x-table.th label="common.label.category"/>
            <x-table.th label="common.label.status" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr>
                <x-table.td :label="$row->name" wire:click="$emit('updateBlog', {{ $row->id }})"/>
                <x-table.td :tags="$row->labels->pluck('name.'.app()->currentLocale())->toArray()"/>
                <x-table.td :status="$row->status->badge()" class="text-right"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-no-result
                title="blog.empty.title"
                subtitle="blog.empty.subtitle"/>
        </x-slot:empty>
    </x-table.tr>

    {!! $this->paginator->links() !!}
</div>