<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Files">
        @if (!$selected)
            <x-button icon="upload" x-on:click="$dispatch('uploader-open')">
                Upload
            </x-button>
        @endif
    </x-page-header>

    @livewire('atom.app.file.uploader', [
        'uid' => 'uploader',
        'title' => 'Upload Files',
        'multiple' => true,
        'sources' => ['device', 'web-image', 'youtube'],
    ])

    <div class="bg-white rounded-lg shadow grid divide-y">
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <x-tabs wire:model="filters.type" wire:loading.class="disabled">
                <x-tabs item>All</x-tabs>
                <x-tabs item>Image</x-tabs>
                <x-tabs item>Video</x-tabs>
                <x-tabs item>Audio</x-tabs>
                <x-tabs item>File</x-tabs>
                <x-tabs item>Youtube</x-tabs>
            </x-tabs>

            @if ($selected)
                <div class="flex flex-wrap items-center gap-2">
                    @if (count($selected) < count($this->files->items()))
                        <x-button wire:click="select('all')" icon="select-multiple" color="gray" inverted>
                            Select All
                        </x-button>
                    @endif
        
                    <x-button wire:click="$set('selected', [])" icon="x" color="gray" inverted>
                        Deselect All
                    </x-button>
        
                    <x-button.delete inverted
                        :label="'Delete ('.count($selected).')'"
                        title="Delete Multiple Files"
                        message="Are you sure to delete the selected {{ count($selected) }} files?"
                    />
                </div>
            @else
                <div>
                    <x-input.search placeholder="Search files"/>
                </div>
            @endif
        </div>
        
        <div class="p-4 grid grid-cols-2 gap-8 md:p-6 md:grid-cols-6 lg:grid-cols-8">
            @forelse ($this->files as $file)
                <div 
                    @if ($selected) wire:click="select({{ $file->id }})"
                    @else wire:click="$set('open', {{ $file->id }})"
                    @endif
                    class="grid gap-1 cursor-pointer"
                >
                    <figure class="relative rounded-md shadow bg-gray-200 pt-[100%] overflow-hidden mb-2">
                        <div class="absolute inset-0 flex items-center justify-center">
                            @if ($file->type === 'youtube')
                                <img src="{{ $file->youtube_thumbnail }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-4 h-4 bg-white"></div>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center text-red-500">
                                    <x-icon name="youtube" type="logo" size="48px" />
                                </div>
                            @elseif ($file->is_video)
                                <video class="w-full h-full object-cover">
                                    <source src="{{ $file->url }}"/>
                                </video>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                        <x-icon name="play" size="28px"/>
                                    </div>
                                </div>
                            @elseif ($file->is_audio)
                                <x-icon name="music" size="48px"/>
                            @elseif ($file->is_image)
                                <img src="{{ $file->url }}" class="h-full w-full object-cover">
                            @elseif ($file->type === 'pdf')
                                <x-icon name="file-pdf" type="solid" size="48px"/>
                            @else
                                <x-icon name="file" size="48px"/>
                            @endif
                        </div>
        
                        @if (in_array($file->id, $selected) || $selected === 'full')
                            <div class="absolute inset-0 bg-black opacity-50"></div>
                        @endif

                        <div class="absolute top-0 left-0 right-0 p-2 bg-gradient-to-b from-gray-500 to-transparent">
                            <a wire:click.stop="select({{ $file->id }})" class="{{ in_array($file->id, $selected) ? 'text-green-500' : 'text-white' }}">
                                <x-icon name="check-circle" type="solid"/>
                            </a>
                        </div>
                    </figure>
    
                    <div class="font-semibold truncate">
                        {{ $file->name }}
                    </div>
                    
                    <div class="text-sm font-semibold text-gray-500 capitalize mb-1.5">
                        {{ $file->type }}
                    </div>
                </div>
            @empty
                <div class="col-span-8">
                    <x-empty-state title="No Files"/>
                </div>
            @endforelse
        </div>
        
        @if ($this->files->hasMorePages())
            <div class="p-4">
                {{ $this->files->links() }}
            </div>
        @endif
    </div>

    <div x-data x-on:drawer-close="$wire.set('open', false)">
        <x-drawer uid="file-form">
            <x-slot name="title">File Details</x-slot>
            @if ($open)
                @livewire('atom.app.file.form', ['fileId' => $open], key('file-form'))
            @endif
        </x-drawer>
    </div>
</div>
