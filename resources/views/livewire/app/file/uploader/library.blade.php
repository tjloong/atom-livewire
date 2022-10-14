<div class="flex flex-col gap-6">
    <x-form.text placeholder="Search"
        wire:model.debounce.300ms="filters.search"
        prefix="icon:search"
        :clear="!empty(data_get($filters, 'search'))"
    />

    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
        @forelse ($this->files as $file)
            <div wire:click="select(@js($file->id))" class="relative rounded-lg shadow overflow-hidden cursor-pointer">
                <x-thumbnail :file="$file"/>

                @if (collect($selected)->contains($file->id))
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="absolute inset-0 flex">
                        <x-icon name="circle-check" size="32" class="m-auto text-green-500"/>
                    </div>
                @endif

                <div class="absolute bottom-0 left-0 right-0 px-2 pb-2 pt-4 bg-gradient-to-t from-black to-transparent opacity-80 overflow-hidden">
                    <div class="truncate text-sm text-white font-medium">{{ $file->name }}</div>
                </div>

            </div>
        @empty
            <div class="col-span-2 md:col-span-4">
                <x-empty-state/>
            </div>
        @endforelse
    </div>

    @if (count($selected))
        <div>
            <x-button.submit type="button" icon="check"
                :label="'Select ('.count($selected).')'"
                wire:click="submit"
            />
        </div>
    @elseif ($this->files->hasPages())
        <div class="flex items-center justify-between gap-4">
            <div>
                @if ($this->files->currentPage() > 1)
                    <a wire:click="$set('page', {{ $page - 1 }})" class="text-gray-800 py-1 px-4 flex items-center gap-2 bg-gray-200 rounded-md">
                        <x-icon name="arrow-left"/> Previous
                    </a>
                @endif
            </div>

            <div>
                @if ($this->files->hasMorePages())
                    <a wire:click="$set('page', {{ $page + 1 }})" class="text-gray-800 py-1 px-4 flex items-center gap-2 bg-gray-200 rounded-md">
                        Next <x-icon name="arrow-right"/>
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
