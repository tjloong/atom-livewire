<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-group>
                        <x-form.select.enum wire:model="filters.mime" label="file.label.mime" enum="file.type"/>
                    </x-group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="app.label.name" sort="name"/>
            <x-table.th label="app.label.size" sort="size" align="right"/>
            <x-table.th label="app.label.created-date" sort="created_at" align="right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $file)
            <x-table.tr wire:click="$emit('updateFile', {{ $file->id }})">
                <x-table.td :checkbox="$file->id"/>

                <x-table.td 
                    :label="$file->name" 
                    limit="50" 
                    :image="$file->is_image ? $file->endpoint.'&sm' : null"
                    :caption="$file->mime">
                </x-table.td>

                <x-table.td :label="$file->filesize" align="right"/>
                <x-table.td :date="$file->created_at" align="right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>
