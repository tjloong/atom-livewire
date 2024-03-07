<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum wire:model="filters.status" label="app.label.status" enum="blog.status"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="app.label.title" sort="name"/>
            <x-table.th label="app.label.category"/>
            <x-table.th label="app.label.status" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $row)
            <x-table.tr wire:click="$emit('updateBlog', {{ $row->id }})">
                <x-table.td :label="$row->name" limit="50" class="font-medium"/>
                <x-table.td :tags="$row->labels->pluck('name.'.app()->currentLocale())->toArray()"/>
                <x-table.td :badges="$row->status->badge()" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table.tr>

    {!! $this->paginator->links() !!}
</div>