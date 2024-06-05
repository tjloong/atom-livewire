<x-form.drawer class="max-w-xl">
@if ($file)
    <x-slot:heading title="Update File"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-group>
        <div class="rounded-lg bg-slate-100 flex flex-col divide-y overflow-hidden shadow">
            @if ($file->is_video || $file->is_image || $file->is_youtube)
                <a href="{{ $file->endpoint }}" target="_blank">
                    <x-image :file="$file" class="h-72 bg-gray-100" sm contain/>
                </a>
            @endif
    
            @if (!$file->is_youtube)
                <x-field label="File Type" :value="$file->mime"/>
                @if ($file->size) <x-field label="File Size" :value="$file->filesize"/> @endif
                @if ($dim = $file->data->dimension ?? null) <x-field label="Dimension" :value="$dim"/> @endif

                <x-anchor label="Download" :href="$file->endpoint" icon="download" target="_blank" align="center" class="py-2"/>
            @endif
        </div>
    </x-group>

    <x-group>
        <x-form.text wire:model.defer="file.name" :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"/>
            
        @if ($file->is_image)
            <x-form.text wire:model.defer="inputs.alt" label="Alt Text" placeholder="Insert Alt Text"/>
            <x-form.text wire:model.defer="inputs.description" placeholder="Insert Image Description"/>
        @endif
    </x-group>
@endif
</x-form.drawer>