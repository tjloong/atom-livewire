<div class="lg:max-w-screen-xl">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <atom:_heading size="lg">@t('files-and-media')</atom:_heading>
                <atom:subheading>@t('storage-used', ['count' => $this->storage])</atom:subheading>
            </div>

            <atom:uploader multiple>
                <atom:_button icon="upload" variant="primary">@t('upload')</atom:_button>
            </atom:uploader>
        </div>

        <livewire:app.file.listing :wire:key="$this->wirekey('app.file.listing')"/>
    </div>

    <livewire:app.file.edit :wire:key="$this->wirekey('app.file.edit')"/>
</div>
