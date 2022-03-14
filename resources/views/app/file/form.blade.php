<div>
    @if ($file->type === 'youtube')
        <figure class="relative rounded-md pt-[60%] shadow overflow-hidden bg-gray-100 mb-4">
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
        <figure class="relative rounded-md pt-[60%] shadow overflow-hidden bg-gray-100 mb-4">
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
        <div class="mb-4">
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

    <x-input.text wire:model.debounce.500ms="file.name" transparent>
        @if ($file->type === 'youtube') Video Name
        @else File Name
        @endif
    </x-input.text>

    @if ($file->type !== 'youtube')
        <x-input.field>
            <x-slot name="label">File Type</x-slot>
            {{ $file->mime }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">
                @if ($path = $file->data->path ?? null) CDN URL
                @else Source
                @endif
            </x-slot>
    
            <a class="block truncate" href="{{ $file->url }}" target="_blank">
                {{ $file->url }}
            </a>
        </x-input.field>

        @if ($file->size)
            <x-input.field>
                <x-slot name="label">File Size</x-slot>
                {{ $file->size }}
            </x-input.field>
        @endif

        @if ($dim = $file->data->dimension ?? null)
            <x-input.field>
                <x-slot name="label">Dimension</x-slot>
                {{ $dim }}
            </x-input.field>
        @endif

        @if ($file->is_image)
            <x-input.text wire:model.debounce.500ms="file.data.alt" transparent placeholder="Insert Alt Text">
                Alt Text
            </x-input.text>

            <x-input.text wire:model.debounce.500ms="file.data.description" transparent placeholder="Insert Image Description">
                Description
            </x-input.text>
        @endif
    @endif

    <div class="flex flex-wrap space-x-2">
        @if ($file->type !== 'youtube')
            <div class="my-1">
                <x-button icon="download" href="{{ $file->url }}" target="_blank">
                    Download
                </x-button>
            </div>
        @endif

        <div class="my-1">
            <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
                title: 'Delete File',
                message: 'Are you sure to delete this file?',
                type: 'error',
                onConfirmed: () => {
                    $dispatch('close')
                    $wire.delete()
                },
            })">
                Delete
            </x-button>
        </div>
    </div>
</div>
