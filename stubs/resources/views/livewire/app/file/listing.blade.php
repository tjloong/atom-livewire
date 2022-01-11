<div class="max-w-screen-xl mx-auto" x-data="fileListing">
    <x-page-header title="Files">
        <div x-show="!selected.length">
            <x-button icon="upload" x-on:click="$dispatch('file-manager-open')">
                Upload
            </x-button>
        </div>
    </x-page-header>

    @livewire('input.file', [
        'title' => 'Upload Files',
        'multiple' => true,
        'sources' => ['device', 'image', 'youtube'],
    ])

    <div class="flex flex-wrap items-center justify-between mb-6">
        <x-tabs wire:model="filterType" wire:loading.class="disabled">
            <x-tabs item>All</x-tabs>
            <x-tabs item>Image</x-tabs>
            <x-tabs item>Youtube</x-tabs>
            <x-tabs item>File</x-tabs>
        </x-tabs>

        <div x-show="selected.length" class="flex flex-wrap items-center space-x-2">
            <x-button icon="select-multiple" color="gray" inverted x-on:click="selectAll()">
                Select All
            </x-button>

            <x-button icon="x" color="gray" inverted x-on:click="selected = []">
                Deselect All
            </x-button>

            <x-button icon="trash" color="red" inverted x-on:click="$dispatch('confirm', {
                title: 'Delete Multiple Files',
                message: `Are you sure to delete the selected ${selected.length} files?`,
                type: 'error',
                onConfirmed: () => {
                    $wire.delete(selected)
                    selected = []
                },
            })">
                Delete (<span x-text="selected.length"></span>)
            </x-button>
        </div>

        <div x-show="!selected.length">
            <x-input.search placeholder="Search files"/>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-8 mb-8 md:grid-cols-6 lg:grid-cols-8">
        @forelse ($files as $file)
            <x-drawer title="File Details" x-on:close.window="close()">
                <x-slot name="trigger">
                    <figure class="relative rounded-md shadow bg-gray-200 pt-[100%] overflow-hidden mb-2" data-file-id="{{ $file->id }}">
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
                            @elseif ($file->is_image)
                                <img src="{{ $file->url }}" class="h-full w-full object-cover">
                            @elseif ($file->type === 'pdf')
                                <x-icon name="file-pdf" type="solid" size="48px"/>
                            @else
                                <x-icon name="file" size="48px"/>
                            @endif
                        </div>

                        <div
                            class="absolute inset-0 bg-black opacity-50"
                            x-show="isSelected({{ $file->id }})"
                            x-on:click.stop="select({{ $file->id }})"
                        ></div>

                        <div class="absolute top-0 left-0 right-0 p-2 bg-gradient-to-b from-gray-500 to-transparent">
                            <a 
                                x-bind:class="isSelected({{ $file->id }}) ? 'text-green-500' : 'text-white'" 
                                x-on:click.stop="select({{ $file->id }})"
                            >
                                <x-icon name="check-circle" type="solid"/>
                            </a>
                        </div>
                    </figure>
                    
                    <div class="font-semibold truncate">
                        {{ $file->name }}
                    </div>
                    
                    <div class="text-xs font-semibold text-gray-500 capitalize mb-1.5">
                        {{ $file->type }}
                    </div>
                </x-slot>

                <div>
                    <livewire:app.file.form :file="$file" :wire:key="$file->id"/>
                </div>
            </x-drawer>
        @empty
            <div class="col-span-8 bg-white rounded-md shadow py-6">
                <x-empty-state title="No Files"/>
            </div>
        @endforelse
    </div>
    
    {{ $files->links() }}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileListing', () => ({
                selected: [],
                select (id) {
                    const index = this.selected.findIndex(val => (val === id))
                    if (index === -1) this.selected.push(id)
                    else this.selected.splice(index, 1)
                },
                selectAll () {
                    this.selected = []
                    this.$root.querySelectorAll('[data-file-id]').forEach(el => {
                        this.selected.push(parseInt(el.getAttribute('data-file-id')))
                    })
                },
                isSelected (id) {
                    return this.selected.some(val => (val === id))
                },
            }))
        })
    </script>
</div>
