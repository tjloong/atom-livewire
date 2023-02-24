<x-drawer uid="file-form-modal" icon="file" header="Update File">
    @if ($file)
        <div class="grid gap-6">
            <div class="rounded-lg bg-slate-100 flex flex-col overflow-hidden shadow">
                @if ($file->is_video || $file->is_image || $file->type === 'youtube')
                    <a href="{{ $file->url }}" target="_blank">
                        <x-thumbnail 
                            :file="$file" 
                            :square="false" 
                            class="rounded-b-none"
                        />
                    </a>
                @endif

                @if ($file->type !== 'youtube')
                    <div class="flex flex-col divide-y">
                        <div class="p-4 grid gap-4">
                            <x-form.field label="File Type">
                                {{ $file->mime }}
                            </x-form.field>
                    
                            <x-form.field label="File URL">
                                <div class="grid">
                                    <a class="truncate" href="{{ $file->url }}" target="_blank">
                                        {{ $file->url }}
                                    </a>
                                </div>
                            </x-form.field>
                    
                            @if ($file->size)
                                <x-form.field label="File Size">
                                    {{ $file->size }}
                                </x-form.field>
                            @endif
                    
                            @if ($dim = $file->data->dimension ?? null)
                                <x-form.field label="Dimension">
                                    {{ $dim }}
                                </x-form.field>
                            @endif
                        </div>

                        <a 
                            target="_blank"
                            href="{{ $file->url }}"
                            class="py-2 px-4 flex items-center justify-center gap-2"
                        >
                            <x-icon name="download"/>
                            {{ __('Download') }}
                        </a>
                    </div>
                @endif
            </div>

            <x-form.text 
                :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"
                wire:model.defer="file.name" 
            />

            @if ($file->is_image)
                <x-form.text 
                    label="Alt Text"
                    wire:model.defer="file.data.alt" 
                    placeholder="Insert Alt Text"
                />

                <x-form.text 
                    label="Description"
                    wire:model.defer="file.data.description" 
                    placeholder="Insert Image Description"
                />
            @endif
        
            <div class="flex items-center justify-between gap-2">
                <x-button.submit type="button"
                    wire:click="submit"
                />

                <x-button.delete inverted
                    title="Delete File"
                    message="Are you sure to delete this file?"
                    :params="$file->id"
                />
            </div>
        </div>
    @endif
</x-drawer>