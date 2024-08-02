<div class="relative max-w-screen-xl">
    <x-file-dropzone multiple/>

    <x-heading lg
        title="app.label.files-and-media"
        subtitle="{{ tr('file.label.storage-used', ['size' => $this->storage_used]) }}">
        <x-button action="file-upload" multiple/>
    </x-heading>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-inputs>
                        <x-select wire:model="filters.mime" label="app.label.file-type" options="enum.file.type"/>
                    </x-inputs>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions>
                <x-button action="delete" invert/>
            </x-table.checkbox-actions>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="app.label.name" sort="name"/>
            <x-table.th label="app.label.size" sort="size" align="right"/>
            <x-table.th label="app.label.created-date" sort="created_at" align="right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $file)
            <x-table.tr wire:click="$emit('editFile', {{ $file->id }})">
                <x-table.td :checkbox="$file->id"/>

                <x-table.td 
                    :label="$file->name" 
                    limit="50" 
                    :image="$file->is_image ? $file->endpoint_sm : null"
                    :caption="$file->mime">
                </x-table.td>

                <x-table.td :label="$file->filesize" align="right"/>
                <x-table.td :date="$file->created_at" align="right"/>
            </x-table.tr>
        @endforeach
    </x-table>
</div>
