<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$account->name" back>
        @accounttype('root')
            <div class="flex items-center gap-2">
                @if ($account->status === 'blocked')
                    <x-button inverted icon="play" x-on:click="$dispatch('confirm', {
                        title: '{{ __('Unblock Account') }}',
                        message: '{{ __('Are you sure to unblock this account?') }}',
                        onConfirmed: () => $wire.unblock(),
                    })">
                        Unblock
                    </x-button>
                @else
                    <x-button inverted icon="block" color="red" x-on:click="$dispatch('confirm', {
                        title: '{{ __('Block Account') }}',
                        message: '{{ __('Are you sure to block this account?') }}',
                        type: 'error',
                        onConfirmed: () => $wire.block(),
                    })">
                        Block
                    </x-button>
                @endif

                <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                    title: '{{ __('Delete Account') }}',
                    message: '{{ __('Are you sure to delete this account?') }}',
                    type: 'error',
                    onConfirmed: () => $wire.delete()
                })">
                    Delete
                </x-button>
            </div>
        @endaccounttype
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($children = data_get($item, 'tabs'))
                        @if ($group = data_get($item, 'group'))
                            <x-sidenav.group :label="$group"/>
                        @endif

                        @foreach ($children as $child)
                            <x-sidenav.item
                                :icon="data_get($child, 'icon')"
                                :name="is_string($child) ? $child : data_get($child, 'slug')"
                                :label="is_string($child) ? str()->headline($child) : data_get($child, 'label')"
                                :href="is_string($child) ? null : data_get($child, 'href')"
                            />
                        @endforeach
                    @else
                        <x-sidenav.item
                            :icon="data_get($item, 'icon')"
                            :name="is_string($item) ? $item : data_get($item, 'slug')"
                            :label="is_string($item) ? str()->headline($item) : data_get($item, 'label')"
                            :href="is_string($item) ? null : data_get($item, 'href')"
                        />
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4 flex flex-col gap-6">
            @if (
                $com = data_get($this->flatTabs->firstWhere('slug', $this->tab), 'livewire')
                    ?? 'app.account.update.'.$tab
            )
                @livewire(lw($com), compact('account'), key($tab))
            @endif
        </div>
    </div>
</div>