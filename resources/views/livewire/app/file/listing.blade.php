<atom:_table :paginate="$this->files">
    @slot ('filters')
        <atom:_select wire:model="filters.mime" label="file-type" options="enum.file-type"/>
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
            <atom:row x-on:click="Atom.modal('app.file.edit').show({{ $file->id }})">
                <atom:cell :checkbox="$file->id"></atom:cell>

                <atom:cell>
                    <atom:file :file="$file"></atom:file>
                </atom:cell>

                <atom:cell align="right">{{ $file->filesize }}</atom:cell>
                <atom:cell align="right">{{ $file->created_at->pretty() }}</atom:cell>
            </atom:row>
        @endforeach
    </atom:rows>
</atom:_table>
