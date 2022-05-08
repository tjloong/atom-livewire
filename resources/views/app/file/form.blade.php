<div class="grid gap-6">
    @if ($file->type === 'youtube')
        <figure class="relative rounded-md pt-[60%] shadow overflow-hidden bg-gray-100">
            <a class="absolute inset-0" href="{{ $file->url }}" target="_blank">
                <img src="{{ $file->youtube_thumbnail }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-white"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center text-red-600">
                    <x-icon name="youtube" type="logo" size="64px"/>
                </div>
            </a>
        </figure>
    @elseif ($file->is_video)
        <figure class="relative rounded-md pt-[60%] shadow overflow-hidden bg-gray-100">
            <a class="absolute inset-0" href="{{ $file->url }}" target="_blank">
                <video class="w-full h-full object-cover">
                    <source src="{{ $file->url }}"/>
                </video>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full text-white flex items-center justify-center">
                        <x-icon name="play" size="28px"/>
                    </div>
                </div>
            </a>
        </figure>
    @elseif ($file->is_image)
        <div>
            <a href="{{ $file->url }}" target="_blank">
                <figure class="relative rounded-md pt-[60%] shadow overflow-hidden bg-gray-100 mb-1.5">
                    <div class="absolute inset-0">
                        <img src="{{ $file->url }}" class="h-full w-full object-cover">
                    </div>
                </figure>
            </a>
            <a class="text-sm" href="{{ $file->url }}" target="_blank">
                View full image
            </a>
        </div>
    @endif

    <x-form.text 
        :label="$file->type === 'youtube' ? 'Video Name' : 'File Name'"
        wire:model.debounce.500ms="file.name" 
        transparent
    />

    @if ($file->type !== 'youtube')
        <x-form.field label="File Type">
            {{ $file->mime }}
        </x-form.field>

        <x-form.field :label="data_get($file->data, 'path') ? 'CDN URL' : 'Source'">
            <a class="block truncate" href="{{ $file->url }}" target="_blank">
                {{ $file->url }}
            </a>
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

        @if ($file->is_image)
            <x-form.text 
                label="Alt Text"
                wire:model.debounce.500ms="file.data.alt" 
                transparent 
                placeholder="Insert Alt Text"
            />

            <x-form.text 
                label="Description"
                wire:model.debounce.500ms="file.data.description" 
                transparent 
                placeholder="Insert Image Description"
            />
        @endif
    @endif

    <div class="flex flex-wrap gap-2">
        @if ($file->type !== 'youtube')
            <x-button icon="download" href="{{ $file->url }}" target="_blank">
                Download
            </x-button>
        @endif

        <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete File',
            message: 'Are you sure to delete this file?',
            type: 'error',
            onConfirmed: () => $wire.emitUp('delete', {{ $file->id }}),
        })">
            Delete
        </x-button>
    </div>
</div>
