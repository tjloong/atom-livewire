<div>
    <x-box>
        <x-slot name="header">Site Announcements</x-slot>
        
        <div class="p-5 grid gap-4">
            @if (collect($announcements)->count())
                <x-input.sortable wire:model="announcements" :config="['handle' => '.sort-handle']" class="grid gap-2">
                    @foreach ($announcements as $announcement)
                        <div class="flex items-center gap-2">
                            <div class="shrink-0 cursor-move sort-handle flex justify-center text-gray-400">
                                <x-icon name="sort-alt-2"/>
                            </div>

                            <div class="grid grow">
                                <a class="truncate" wire:click="edit('{{ data_get($announcement, 'uid') }}')">
                                    {{ data_get($announcement, 'content') }}
                                </a>
                            </div>
                            <x-badge>{{ data_get($announcement, 'is_active') ? __('active') : __('inactive') }}</x-badge>
                        </div>
                    @endforeach
                </x-input.sortable>
            @endif

            <div class="flex items-center gap-2">
                <x-button color="gray" icon="plus" wire:click="create">
                    {{ __('Add Announcement') }}
                </x-button>
            </div>
        </div>
    </x-box>

    <form wire:submit.prevent="submit">
        <x-modal>
            <x-slot:title>{{ data_get($form, 'uid') ? 'Update' : 'Create' }} Announcement</x-slot:title>

            @if ($form)
                <x-input.select
                    wire:model="form.type"
                    :options="$this->types"
                    :error="$errors->first('form.type')"
                    required
                >
                    {{ __('Announcement Type') }}
                </x-input.select>

                <x-input.textarea wire:model.defer="form.content" :error="$errors->first('form.content')" required>
                    {{ __('Announcement Content') }}
                </x-input.textarea>

                <x-input.text wire:model.defer="form.url">
                    {{ __('Announcement Link') }}
                </x-input.text>

                <x-input.checkbox wire:model="form.is_active">
                    {{ __('Announcement is active') }}
                </x-input.checkbox>
            @endif
    
            <x-slot:buttons>
                <div class="flex items-center gap-2">
                    <x-button color="green" icon="check" type="submit">
                        Save
                    </x-button>

                    @if ($uid = data_get($form, 'uid'))
                        <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
                            title: '{{ __('Delete Announcement') }}',
                            message: '{{ __('Are you sure to delete this announcement?') }}',
                            type: 'error',
                            onConfirmed: () => $wire.delete('{{ $uid }}'),
                        })">
                            Delete
                        </x-button>
                    @endif
                </div>
            </x-slot:buttons>
        </x-modal>
    </form>
</div>
