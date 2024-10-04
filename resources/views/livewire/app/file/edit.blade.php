<atom:modal name="edit-file" variant="slide" wire:open="open">
@if ($file)
    <atom:_form>        
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <atom:_button action="submit">@t('save')</atom:_button>
                <atom:_button action="delete" inverted>@t('delete')</atom:_button>
            </div>
    
            <atom:separator/>

            <atom:_heading size="xl">@t('edit-file')</atom:_heading>

            <x-box>
                @if ($file->is_video || $file->is_image || $file->is_youtube)
                    <x-slot:figure>
                        <x-image :file="$file" sm contain/>
                    </x-slot:figure>
                @endif

                @if (!$file->is_youtube)
                    <div class="flex flex-col divide-y *:py-2 *:px-4">
                        <x-field label="File Type" :value="$file->mime"/>
                        @if ($file->size) <x-field label="File Size" :value="$file->filesize"/> @endif
                        @if ($dim = $file->data->dimension ?? null) <x-field label="Dimension" :value="$dim"/> @endif
                        <x-anchor label="Download" :href="$file->endpoint" icon="download" target="_blank" align="center" class="py-2"/>
                    </div>
                @endif
            </x-box>

            <x-input wire:model.defer="file.name" :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"/>
                
            @if ($file->is_image)
                <x-input wire:model.defer="inputs.alt" label="Alt Text" placeholder="Insert Alt Text"/>
                <x-input wire:model.defer="inputs.description" placeholder="Insert Image Description"/>
            @endif
        </div>
    </atom:_form>
@endif
</atom:modal>