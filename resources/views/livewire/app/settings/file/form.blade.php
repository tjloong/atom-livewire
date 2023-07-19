<x-drawer id="file-form-modal" icon="file" header="Update File">
    @if ($file)
        <div class="p-4 grid gap-6">
            <div class="rounded-lg bg-slate-100 flex flex-col overflow-hidden shadow">
                @if ($file->is_video || $file->is_image || $file->type === 'youtube')
                    <a href="{{ $file->url }}" target="_blank">
                        <x-thumbnail 
                            :file="$file" 
                            :square="false" 
                            class="rounded-b-none w-full h-32"
                        />
                    </a>
                @endif

                @if ($file->type !== 'youtube')
                    <div class="flex flex-col divide-y text-sm">
                        <x-field label="File Type" :value="$file->mime"/>
                        @if ($file->size) <x-field label="File Size" :value="$file->size"/> @endif
                        @if ($dim = $file->data->dimension ?? null) <x-field label="Dimension" :value="$dim"/> @endif

                        <x-link label="Download" :href="$file->url" icon="download" target="_blank" class="py-2 px-4 self-center"/>
                    </div>
                @endif
            </div>

            <x-form.text wire:model.defer="file.name" :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"/>

            @if ($file->is_image)
                <x-form.text wire:model.defer="file.data.alt" label="Alt Text" placeholder="Insert Alt Text"/>
                <x-form.text wire:model.defer="file.data.description" placeholder="Insert Image Description"/>
            @endif
        
            <div class="flex items-center justify-between gap-2">
                <x-button.submit type="button" wire:click="submit"/>

                <x-button.delete inverted
                    title="Delete File"
                    message="Are you sure to delete this file?"
                    :params="$file->id"
                />
            </div>
        </div>
    @endif
</x-drawer>