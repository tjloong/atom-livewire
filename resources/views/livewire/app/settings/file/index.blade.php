<div class="max-w-screen-xl">
    <x-heading title="file.heading.file">
        <x-form.file.uploader :dropzone="false"/>
    </x-heading>

    @livewire('app.settings.file.listing', key('listing'))
</div>
