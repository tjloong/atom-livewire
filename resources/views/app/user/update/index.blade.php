<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$user->name" back>
        @if ($user->id !== auth()->id())
            <div class="flex items-center gap-2">
                @if ($user->status === 'trashed')
                    <x-button color="gray" icon="trash-arrow-up" 
                        label="Restore"
                        wire:click="restore"
                    />
    
                    <x-button.delete inverted
                        label="Force Delete"
                        title="Force Delete User"
                        message="This will permanently delete the user. Are you sure?"
                        :params="true"
                    />
                @else
                    @if ($user->status === 'blocked')
                        <x-button.confirm color="gray" icon="play"
                            label="Unblock"
                            title="Unblock User"
                            message="Are you sure to unblock this user?"
                            callback="unblock"
                        />
                    @else
                        <x-button.confirm color="red" icon="block" inverted
                            label="Block"
                            title="Block User"
                            message="Are you sure to block this user?"
                            callback="block"
                        />
                    @endif

                    <x-button.delete inverted
                        title="Delete User"
                        message="Are you sure to delete this user?"
                    />
                @endif
            </div>
        @endif
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        @if ($this->tabs)
            <div class="md:col-span-3">
                <x-sidenav wire:model="tab">
                    @foreach ($this->tabs as $item)
                        <x-sidenav.item
                            :name="data_get($item, 'slug')"
                            :label="data_get($item, 'label')"
                        />
                    @endforeach
                </x-sidenav>
            </div>
        @endif

        <div class="{{ $this->tabs ? 'md:col-span-9' : 'md:col-span-12' }}">
            @if ($com = lw([
                'info' => 'app.user.update.info',
                'permissions' => 'app.permission.listing',
            ][$tab]))
                @livewire($com, compact('user'), key($tab))
            @endif
        </div>
    </div>
</div>