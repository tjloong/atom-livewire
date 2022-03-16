<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$account->name" back>
        <div class="flex items-center gap-2">
            @if ($account->status === 'blocked')
                <x-button inverted icon="play" x-on:click="$dispatch('confirm', {
                    title: 'Unblock Account',
                    message: 'Are you sure to unblock this account?',
                    onConfirmed: () => $wire.unblock().then(() => window.location.reload()),
                })">
                    Unblock
                </x-button>
            @elseif ($account->status !== 'trashed')
                @can('account.block')
                    <x-button inverted icon="block" color="red" x-on:click="$dispatch('confirm', {
                        title: 'Block Account',
                        message: 'Are you sure to block this account?',
                        type: 'error',
                        onConfirmed: () => $wire.block().then(() => window.location.reload()),
                    })">
                        Block
                    </x-button>
                @endcan
            @endif

            <x-button inverted icon="trash" color="red" 
                can="account.delete"
                :hide="$account->status === 'trashed'"
                x-on:click="$dispatch('confirm', {
                    title: 'Delete Account',
                    message: 'Are you sure to delete this account?',
                    type: 'error',
                    onConfirmed: () => $wire.delete()
                })"
            >
                Delete
            </x-button>
        </div>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @foreach ($navs as $nav)
                    @if(property_exists($nav, 'group'))
                        <x-sidenav :group="$nav->group">
                            @foreach ($nav->tabs as $val)
                                <x-sidenav item :href="route('app.account.update', [$account->id, $val->slug])">
                                    {{ $val->label ?? str($val->slug)->headline() }}
                                </x-sidenav>
                            @endforeach
                        </x-sidenav>
                    @else
                        <x-sidenav item :href="route('app.account.update', [$account->id, $nav->slug])">
                            {{ $nav->label ?? str($nav->slug)->headline() }}
                        </x-sidenav>
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($component = get_livewire_component($tab, 'app/account/update'))
                @livewire($component, compact('account'))
            @endif
        </div>
    </div>
</div>