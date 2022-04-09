<div>
    <div class="relative mb-6">
        <div class="text-gray-400 absolute top-0 left-0 bottom-0 flex items-center justify-center px-2">
            <x-icon name="search" size="18px"/>
        </div>

        <input 
            wire:model.debounce.300ms="search"
            type="text" 
            class="form-input w-full px-10" 
            placeholder="Search"
        >

        @if (!empty($search))
            <a wire:click="$set('search', '')" class="text-gray-500 absolute top-0 right-0 bottom-0 flex items-center justify-center px-2">
                <x-icon name="x" size="18px"/>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 mb-4 md:grid-cols-4">
        @forelse ($this->files as $file)
            <div wire:click="select({{ $file->id }})" class="rounded-md shadow overflow-hidden pt-[100%] relative cursor-pointer bg-gray-100">
                @if ($file->is_image)
                    <div class="absolute inset-0">
                        <img src="{{ $file->url }}" class="w-full h-full object-cover">
                    </div>
                @elseif ($file->is_video)
                    <div class="absolute inset-0">
                        <video class="w-full h-full object-cover">
                            <source src="{{ $file->url }}"/>
                        </video>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full text-white flex items-center justify-center">
                                <x-icon name="play" size="28px"/>
                            </div>
                        </div>
                    </div>
                @elseif ($file->type === 'youtube')
                    <div class="absolute inset-0 flex items-center justify-center">
                        @if ($vid = $file->data->vid ?? null)
                            <img src="https://img.youtube.com/vi/{{ $vid }}/default.jpg" class="w-full h-full object-cover">
                        @endif

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4 h-4 bg-white"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center text-red-500">
                            <x-icon name="youtube" type="logo" size="32px"/>
                        </div>
                    </div>
                @elseif ($file->type === 'pdf')
                    <div class="absolute inset-0 flex items-center justify-center">
                        <x-icon name="file-pdf" type="solid" size="64px"/>
                    </div>
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <x-icon name="file" type="solid" size="64px"/>
                    </div>
                @endif

                @if (collect($selected)->contains($file->id))
                    <div class="absolute inset-0 flex items-center justify-center text-green-500">
                        <div class="absolute inset-0 bg-black opacity-50"></div>
                        <div class="relative">
                            <x-icon name="check-circle" type="solid" size="32px"/>
                        </div>
                    </div>
                @endif

                <div class="absolute bottom-0 left-0 right-0 px-2 pb-2 pt-4 text-white bg-gradient-to-t from-black to-transparent opacity-80 overflow-hidden">
                    <div class="truncate text-sm">{{ $file->name }}</div>
                </div>
            </div>
        @empty
            <div class="col-span-2 md:col-span-4">
                <x-empty-state/>
            </div>
        @endforelse
    </div>

    @if ($selected)
        <x-button wire:click="submit" color="green" icon="check">
            Select ({{ count($selected) }})
        </x-button>
        
    @elseif ($this->files->hasPages())
        <div class="flex items-center justify-between gap-4">
            <div>
                @if ($this->files->currentPage() > 1)
                    <a wire:click="$set('page', {{ $page - 1 }})" class="text-gray-800 py-1 px-4 flex items-center gap-2 bg-gray-200 rounded-md">
                        <x-icon name="left-arrow-alt"/> Previous
                    </a>
                @endif
            </div>

            <div>
                @if ($this->files->hasMorePages())
                    <a wire:click="$set('page', {{ $page + 1 }})" class="text-gray-800 py-1 px-4 flex items-center gap-2 bg-gray-200 rounded-md">
                        Next <x-icon name="right-arrow-alt"/>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
