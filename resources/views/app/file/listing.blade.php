<div 
    x-data
    x-on:uploader-completed.window="location.reload()"
    class="max-w-screen-xl mx-auto"
>
    <x-page-header title="Files">
        @if (!$selected)
            <x-button label="Upload" x-on:click="$dispatch('uploader-open')"/>
        @endif
    </x-page-header>

    @livewire(lw('app.file.uploader'), [
        'uid' => 'uploader',
        'title' => 'Upload Files',
        'multiple' => true,
        'sources' => ['device', 'web-image', 'youtube'],
    ])

    <div class="bg-white rounded-lg shadow grid divide-y">
        <div class="p-4 flex flex-wrap items-center justify-between gap-4">
            <x-tab wire:model="filters.type">
                @foreach ([
                    'all',
                    'image',
                    'video',
                    'audio',
                    'file',
                    'youtube',
                ] as $item)
                    <x-tab.item :name="$item === 'all' ? null : $item" :label="str()->headline($item)"/>
                @endforeach
            </x-tab>

            @if ($selected)
                <div class="flex flex-wrap items-center gap-2">
                    @if (count($selected) < count($this->files->items()))
                        <x-button color="gray" inverted
                            label="Select All"
                            wire:click="select('all')"
                        />
                    @endif
        
                    <x-button color="gray" inverted
                        label="Deselect All"
                        wire:click="$set('selected', [])"
                    />
        
                    <x-button.delete inverted
                        :label="'Delete ('.count($selected).')'"
                        title="Delete Multiple Files"
                        message="Are you sure to delete the selected {{ count($selected) }} files?"
                    />
                </div>
            @else
                <x-form.text placeholder="Search Files"
                    prefix="icon:search"
                    wire:model.debounce.300ms="filters.search"
                    :clear="!empty(data_get($filters, 'search'))"
                />
            @endif
        </div>
        
        <div class="p-4 grid grid-cols-2 gap-8 md:p-6 md:grid-cols-6 lg:grid-cols-8">
            @forelse ($this->files as $file)
                <div 
                    @if ($selected) wire:click="select({{ $file->id }})"
                    @else wire:click="$emitTo('atom.app.file.form', 'open', {{ $file->id }})"
                    @endif
                    class="grid gap-1 cursor-pointer"
                >
                    <div class="relative rounded-md overflow-hidden shadow">
                        <x-thumbnail :file="$file"/>

                        @if (in_array($file->id, $selected) || $selected === 'full')
                            <div class="absolute inset-0 bg-black/50"></div>
                        @endif

                        <div
                            wire:click.stop="select(@js($file->id))"
                            class="absolute top-0 left-0 right-0 p-2 bg-gradient-to-b from-gray-500 to-transparent cursor-pointer"
                        >
                            <div class="flex items-center justify-between">
                                <x-icon name="circle-check" class="{{ in_array($file->id, $selected) ? 'text-green-500' : 'text-white' }}"/>

                                @if ($type = $file->type)
                                    <x-badge :label="$type" size="xs"/>
                                @endif
                            </div>
                        </div>
                    </div>
    
                    <div class="grid">
                        <div class="font-medium text-gray-500 px-1 truncate text-sm">
                            {{ $file->name }}
                        </div>
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

    @livewire('atom.app.file.form', key('file-form'))
</div>
