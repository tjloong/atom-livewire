<div
    x-data="{ show: false }"
    class="max-w-screen-xl mx-auto"
>
    <x-box header="Files and Media" class="rounded-lg">
        <x-slot:buttons>
            @if (!$selected)
                <x-button color="gray" 
                    label="Upload" 
                    x-on:click="show = !show"
                />
            @endif
        </x-slot:buttons>
        
        <div class="flex flex-col divide-y">
            <div class="p-4 flex items-center justify-between gap-3">
                <div class="shrink-0">
                    <div class="flex items-center gap-2">
                        <x-form.select
                            wire:model="filters.type"
                            :options="collect(['image', 'video', 'audio', 'file', 'youtube'])
                                ->map(fn($val) => ['value' => $val, 'label' => ucfirst($val)])"
                            placeholder="All Types"
                        />

                        @if ($count = count($selected))
                            <div class="flex items-center divide-x divide-gray-300 bg-gray-200 text-sm text-black font-medium rounded-full">
                                <div class="flex items-center gap-2 py-1 px-3">
                                    <x-icon name="check" class="text-green-500"/>
                                    <div class="flex items-center gap-1 font-medium">
                                        {{ __(':count Selected', ['count' => $count]) }}
                                    </div>
                                </div>
                            
                                <div wire:click="select('*')" class="flex items-center gap-2 justify-center py-1 px-3 cursor-pointer">
                                    <x-icon name="check-double" class="text-gray-400" size="12"/>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                    
                @if ($count)
                    <div class="shrink-0">
                        <x-button.delete inverted
                            :label="'Delete ('.$count.')'"
                            title="Delete Multiple Files"
                            message="Are you sure to delete the selected {{ $count }} files?"
                        />
                    </div>
                @else
                    <div class="shrink-0">
                        <x-form.text placeholder="Search Files"
                            prefix="icon:search"
                            wire:model.debounce.300ms="filters.search"
                            :clear="!empty(data_get($filters, 'search'))"
                        />
                    </div>
                @endif
            </div>

            <div x-show="show" class="p-4">
                <x-form.file :library="false" multiple>
                    <x-slot:list></x-slot:list>
                </x-form.file>
            </div>

            <div class="p-4 flex items-center justify-center gap-6 flex-wrap">
                @forelse ($this->files as $file)
                    <div wire:click="edit({{ $file->id }})" class="flex flex-col gap-1 cursor-pointer">
                        <div class="relative rounded-md overflow-hidden shadow">
                            <x-thumbnail :file="$file" size="125"/>

                            @if (in_array($file->id, $selected) || $selected === 'full')
                                <div class="absolute inset-0 bg-black/50"></div>
                            @endif

                            <div
                                wire:click.stop="select({{ $file->id }})"
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
        
                        <div class="font-medium text-gray-500 px-1 text-sm">
                            {{ str($file->name)->limit(12) }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-6">
                        <x-empty-state title="No Files" subtitle="File list is empty"/>
                    </div>
                @endforelse
            </div>
        </div>
    </x-box>

    {!! $this->files->links() !!}

    @livewire(lw('app.settings.system.file-form-modal'), key('file-form'))
</div>
