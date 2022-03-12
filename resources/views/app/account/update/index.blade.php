<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$user->account->name ?? $user->name" back>
        <x-button inverted icon="trash" color="red" 
            can="account.delete"
            :hide="$user->account->status === 'trashed'"
            x-on:click="$dispatch('confirm', {
                title: 'Delete Account',
                message: 'Are you sure to delete this account?',
                type: 'error',
                onConfirmed: () => $wire.delete()
            })"
        >
            Delete
        </x-button>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @foreach ($tabs as $key => $val)
                    <x-sidenav item href="{{ route('app.account.update', [$user->id, $key]) }}">{{ $val }}</x-sidenav>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($component = get_livewire_component($tab, 'app/account/update'))
                @livewire($component, compact('user'))
            @endif
        </div>
    </div>
</div>