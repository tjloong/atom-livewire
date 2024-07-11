<x-drawer wire:submit.prevent="submit" wire:close="$emit('closeFile')">
@if ($file)
    <x-slot:heading title="app.label.edit-file"></x-slot:heading>

    <x-slot:buttons>
        <x-button action="submit"/>
        <x-button action="delete" no-label invert/>
    </x-slot:buttons>

    <div class="p-5">
        <x-box>
            @if ($file->is_video || $file->is_image || $file->is_youtube)
                <x-slot:figure>
                    <x-image :file="$file" sm contain/>
                </x-slot:figure>
            @endif

            @if (!$file->is_youtube)
                <x-fieldset>
                    <x-field label="File Type" :value="$file->mime"/>
                    @if ($file->size) <x-field label="File Size" :value="$file->filesize"/> @endif
                    @if ($dim = $file->data->dimension ?? null) <x-field label="Dimension" :value="$dim"/> @endif
                    <x-anchor label="Download" :href="$file->endpoint" icon="download" target="_blank" align="center" class="py-2"/>
                </x-fieldset>
            @endif
        </x-box>
    </div>

    <x-fieldset inputs>
        <x-input wire:model.defer="file.name" :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"/>
            
        @if ($file->is_image)
            <x-input wire:model.defer="inputs.alt" label="Alt Text" placeholder="Insert Alt Text"/>
            <x-input wire:model.defer="inputs.description" placeholder="Insert Image Description"/>
        @endif
    </x-fieldset>
@endif
</x-drawer>