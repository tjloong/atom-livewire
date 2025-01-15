<div class="lg:max-w-screen-xl">
    <atom:_table :paginate="$this->files">
        @slot('total')
            <atom:_heading size="xl">
                @t('files-manager')
            </atom:_heading>
        @endslot

        @slot ('filters')
            <atom:_select wire:model="filters.mime" label="file-type" options="enum.file-type"/>
        @endslot

        @slot ('bar')
            <atom:uploader multiple>
                <atom:_button icon="upload" size="sm">@t('upload')</atom:_button>
            </atom:uploader>
        @endslot
    
        @slot ('actions')
            <atom:_button action="delete" size="sm" inverted>@t('delete')</atom:_button>
        @endslot
    
        <atom:columns>
            <atom:column checkbox/>
            <atom:column sort="name">Name</atom:column>
            <atom:column sort="size" align="right">Size</atom:column>
            <atom:column sort="created_at" align="right">Created Date</atom:column>
        </atom:columns>
    
        <atom:rows>
            @foreach ($this->files as $file)
                <atom:row x-on:click="Atom.modal('atom.file.edit').slide({{ js($file->id) }})">
                    <atom:cell :checkbox="$file->id"></atom:cell>
    
                    <atom:cell>
                        <atom:file :file="$file"></atom:file>
                    </atom:cell>
    
                    <atom:cell align="right">@e($file->size)</atom:cell>
                    <atom:cell align="right">@e($file->created_at->pretty())</atom:cell>
                </atom:row>
            @endforeach
        </atom:rows>

        @slot ('footer')
            <atom:subheading>@t('storage-used', ['count' => $this->storage])</atom:subheading>
        @endslot
    </atom:_table>

    <livewire:atom.file.edit :wire:key="$this->wirekey('atom.file.edit')"/>
</div>
