<div class="max-w-screen-xl">
    <x-heading 
        title="app.label.files-and-media"
        subtitle="{{ tr('file.label.storage-used', ['size' => $this->storage_used]) }}">
        <x-form.file.uploader :dropzone="false"/>
    </x-heading>

    @livewire('app.file.listing', key('listing'))
</div>
