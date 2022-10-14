<div>
    <x-box header="Website Announcement">
        <x-slot:header-buttons>
            <x-button size="sm" 
                label="New Announcement"
                wire:click="open"
            />
        </x-slot:header-buttons>

        @if ($announcements->count())
            <x-form.sortable 
                wire:sorted="sort" 
                :config="['handle' => '.sort-handle']" 
                class="grid divide-y"
            >
                @foreach ($announcements as $announcement)
                    <div 
                        class="p-4 flex items-center gap-2 hover:bg-slate-100" 
                        data-sortable-id="{{ data_get($announcement, 'uuid') }}"
                    >
                        <div class="shrink-0 cursor-move sort-handle flex text-gray-400">
                            <x-icon name="sort" class="m-auto"/>
                        </div>

                        <div class="grid grow">
                            <a class="truncate" wire:click="open(@js(data_get($announcement, 'uuid')))">
                                {{ data_get($announcement, 'content') }}
                            </a>
                        </div>

                        <div class="shrink-0 flex items-center gap-2">
                            <x-badge :label="data_get($announcement, 'is_active') ? 'active' : 'inactive'"/>
                            <x-button.delete size="xs" inverted 
                                title="Delete Announcement"
                                message="Are you sure to delete this announcement?"
                                :params="data_get($announcement, 'uuid')"
                            />
                        </div>
                    </div>
                @endforeach
            </x-form.sortable>
        @else
            <x-empty-state/>
        @endif
    </x-box>

    <x-modal form
        uid="announcement-form-modal" 
        :header="data_get($input, 'uid') ? 'Update Announcement' : 'Create Announcement'"
    >
        @if ($input)
            <div class="grid gap-6">
                <x-form.select
                    label="Announcement Type"
                    wire:model="input.type"
                    :options="$this->types"
                    :error="$errors->first('input.type')"
                    required
                />

                <x-form.textarea 
                    label="Announcement Content"
                    wire:model.defer="input.content" 
                    :error="$errors->first('input.content')" 
                    required
                />

                <x-form.text 
                    label="Announcement Link"
                    wire:model.defer="input.url"
                />

                <x-form.checkbox 
                    label="Announcement is active"
                    wire:model="input.is_active"
                />
            </div>

            <x-slot:foot>
                <x-button.submit/>
            </x-slot:foot>
        @endif
    </x-modal>
</div>
