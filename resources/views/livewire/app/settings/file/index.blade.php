<div class="max-w-screen-xl">
    <x-heading title="Files and Media"/>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            <x-table.checkbox-actions delete/>
            <x-table.toolbar>
                <x-form.select.enum enum="file.type" :label="false"
                    wire:model="filters.mime"
                    placeholder="All Types"/>
                
                <x-button label="Upload" icon="upload" x-on:click="$dispatch('uploader-open')"/>
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th checkbox/>
            <x-table.th label="File Name" sort="name"/>
            <x-table.th label="Size" sort="size" class="text-right"/>
            <x-table.th label="Created Date" sort="created_at" class="text-right"/>
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

    <x-modal id="uploader" class="max-w-screen-sm p-5">
        <x-slot:heading title="Files Uploader"></x-slot:heading>
        <div 
            x-on:files-uploaded="$dispatch('close')"
            x-on:files-created="$dispatch('close')">
            <x-form.file wire:model="files" enable-url multiple/>
        </div>
    </x-modal>

    @livewire('app.settings.file.update', key('file-update'))
</div>
