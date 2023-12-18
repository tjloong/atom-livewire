<x-form.drawer id="file-update" class="max-w-xl w-full">
@if ($file)
    <x-slot:heading title="Update File"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-form.group>
        <div class="rounded-lg bg-slate-100 flex flex-col overflow-hidden shadow">
            @if ($file->is_video || $file->is_image || $file->type === 'youtube')
                <a href="{{ $file->url }}" target="_blank">
                    <x-image :src="$file" class="rounded-b-none" height="300"/>
                </a>
            @endif
    
            @if ($file->type !== 'youtube')
                <div class="flex flex-col divide-y text-sm">
                    <x-field label="File Type" :value="$file->mime"/>
                    @if ($file->size) <x-field label="File Size" :value="$file->size"/> @endif
                    @if ($dim = $file->data->dimension ?? null) <x-field label="Dimension" :value="$dim"/> @endif

                    <div class="flex items-center justify-center p-3">
                        <x-link label="Download" :href="$file->url" icon="download" target="_blank"/>
                    </div>

                </div>
            @endif
        </div>
    </x-form.group>

    <x-form.group>
        <x-form.text wire:model.defer="file.name" :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"/>
            
        @if ($file->is_image)
            <x-form.text wire:model.defer="inputs.alt" label="Alt Text" placeholder="Insert Alt Text"/>
            <x-form.text wire:model.defer="inputs.description" placeholder="Insert Image Description"/>
        @endif
    </x-form.group>
@endif
</x-form.drawer>