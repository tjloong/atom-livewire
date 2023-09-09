<div class="w-full">
    <x-page-header title="Website Announcements">
        <x-button
            label="New Announcement"
            wire:click="$emitTo('{{ atom_lw('app.settings.website.announcement-modal') }}', 'open')"
        />
    </x-page-header>

    <x-box>
        @if (count($announcements))
            <x-form.sortable wire:sorted="sort" :config="['handle' => '.cursor-move']" class="flex flex-col divide-y">
                @foreach ($announcements as $announcement)
                    <div class="p-4 flex items-center gap-2 hover:bg-slate-100" data-sortable-id="{{ data_get($announcement, 'uuid') }}">
                        <div class="shrink-0 cursor-move flex text-gray-400">
                            <x-icon name="sort" class="m-auto"/>
                        </div>
    
                        <div class="grow flex items-center justify-between gap-2">
                            <div class="grid">
                                <a class="truncate" wire:click="$emitTo('{{ atom_lw('app.settings.website.announcement-modal') }}', 'open', @js($announcement))">
                                    {{ data_get($announcement, 'title') }}
                                </a>
                                <div class="text-sm text-gray-500 font-medium truncate">
                                    {{ data_get($announcement, 'type') }}
                                </div>
                            </div>
    
                            <div class="shrink-0">
                                <x-badge :label="data_get($announcement, 'is_active') ? 'active' : 'inactive'"/>
                            </div>
                        </div>
    
                        <div class="shrink-0">
                            <x-close.delete 
                                :params="data_get($announcement, 'uuid')"
                                title="Delete Announcement"
                                message="Are you sure to delete this announcement?"
                            />
                        </div>
                    </div>
                @endforeach
            </x-form.sortable>
        @else
            <x-noresult/>
        @endif
    </x-box>

    @livewire(atom_lw('app.settings.website.announcement-modal'))
</div>
