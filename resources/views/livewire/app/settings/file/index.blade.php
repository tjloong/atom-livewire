<div class="max-w-screen-xl">
    <x-heading 
        title="file.heading.file"
        subtitle="{{ tr('file.label.storage-used', ['size' => $this->storage_used]) }}">
        <x-form.file.uploader :dropzone="false"/>
    </x-heading>

    @livewire('app.settings.file.listing', key('listing'))
</div>
