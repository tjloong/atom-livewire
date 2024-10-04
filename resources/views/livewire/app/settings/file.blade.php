<div class="lg:max-w-screen-xl">
    <div class="flex items-center justify-between mb-4">
        <div>
            <atom:_heading size="lg">Files and Media</atom:_heading>
            <atom:subheading>{{ $this->storage }} Used</atom:subheading>
        </div>

        <atom:uploader multiple>
            <atom:_button icon="upload" variant="primary">@t('upload')</atom:_button>
        </atom:uploader>
    </div>

    <livewire:app.file.listing wire:key="listing"/>
    <livewire:app.file.edit wire:key="edit"/>
</div>
