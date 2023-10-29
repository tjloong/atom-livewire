<div>
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.filters>
                    <x-form.group>
                        <x-form.select.enum label="file.label.mime" enum="file.type"
                            wire:model="filters.mime"/>
                    </x-form.group>
                </x-table.filters>
            </x-table.searchbar>

            <x-table.checkbox-actions delete/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="common.label.name" sort="name"/>
            <x-table.th label="common.label.size" sort="size" class="text-right"/>
            <x-table.th label="common.label.created-date" sort="created_at" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $file)
            <x-table.tr>
                <x-table.td :checkbox="$file->id"/>
                <x-table.td>
                    <div class="flex items-center gap-3">
                        <x-thumbnail :file="$file" size="30"/>
                        <div class="grow">
                            <x-link :label="str($file->name)->limit(50)"
                                wire:click="$emit('updateFile', {{ $file->id }})"/>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $file->mime }}
                            </div>
                        </div>
                    </div>
                </x-table.td>
                <x-table.td :label="$file->size ?? '--'" class="text-right"/>
                <x-table.td :date="$file->created_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}
</div>
