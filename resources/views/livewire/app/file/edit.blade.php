<atom:modal name="app.file.edit" variant="slide" wire:open="open">
@if ($file)
    <atom:_form>        
        <atom:_heading size="xl">@t('edit-file')</atom:_heading>

        <atom:card inset>
            <x-slot:cover class="h-80">
                <atom:embed :file="$file"/>
            </x-slot:cover>

            @if (!$file->is_youtube)
                <div class="py-3 px-6">
                    <atom:dd label="file-type">@e($file->mime)</atom:dd>

                    @if ($file->size)
                        <atom:dd label="file-size">@e($file->filesize)</atom:dd>
                    @endif

                    @if ($dim = $file->data->dimension ?? null)
                        <atom:dd label="dimension">@e($dim)</atom:dd>
                    @endif

                    <atom:_button :href="$file->endpoint" icon="download" variant="link" block newtab>
                        @t('download')
                    </atom:_button>
                </div>
            @endif
        </atom:card>

        <atom:_input wire:model.defer="file.name" :label="$file->is_youtube ? 'Video Name' : 'File Name'"/>
            
        @if ($file->is_image)
            <atom:_input wire:model.defer="file.data.alt" label="Alt Text"/>
            <atom:_input wire:model.defer="file.data.description" label="Description"/>
        @endif

        <div class="flex items-center gap-2">
            <atom:_button action="submit">@t('save')</atom:_button>
            <atom:_button action="delete" inverted>@t('delete')</atom:_button>
        </div>
    </atom:_form>
@endif
</atom:modal>