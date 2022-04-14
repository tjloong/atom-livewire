<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$account->name" back>
        <div class="flex items-center gap-2">
            @can('account.block')
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
            @endcan

            @can('account.delete')
                <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                    title: '{{ __('Delete Account') }}',
                    message: '{{ __('Are you sure to delete this account?') }}',
                    type: 'error',
                    onConfirmed: () => $wire.delete()
                })">
                    Delete
                </x-button>
            @endcan
        </div>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($children = data_get($item, 'tabs'))
                        <x-sidenav :group="$item['group'] ?? null">
                            @foreach ($children as $child)
                                @if ($slug = data_get($child, 'slug') ?? $child)
                                    <x-sidenav item :icon="data_get($child, 'icon')" :name="$slug">
                                        {{ data_get($child, 'label') ?? str()->headline($slug) }}
                                    </x-sidenav>
                                @endif
                            @endforeach
                        </x-sidenav>
                    @elseif ($slug = data_get($item, 'slug') ?? $item)
                        <x-sidenav item :icon="data_get($item, 'icon')" :name="$slug">
                            {{ data_get($item, 'label') ?? json_encode($slug) }}
                        </x-sidenav>
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($component = livewire_name('app/account/update/'.$tab))
                @livewire($component, compact('account'), key($tab))
            @endif
        </div>
    </div>
</div>