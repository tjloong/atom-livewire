<div class="max-w-lg mx-auto">
    <x-page-header :title="$user->name" back>
        <x-button inverted icon="trash" color="red" 
            can="signup.delete"
            :hide="$user->signup->status === 'trashed'"
            x-on:click="$dispatch('confirm', {
                title: 'Delete Sign-Up',
                message: 'Are you sure to delete this sign-up?',
                type: 'error',
                onConfirmed: () => $wire.delete()
            })"
        >
            Delete
        </x-button>
    </x-page-header>

    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-slot name="label">Name</x-slot>

                <div class="grid gap-1">
                    <div>{{ $user->name }}</div>

                    @if ($user->signup->status === 'blocked')
                        <div class="flex gap-2">
                            <div class="text-red-500 text-xs flex gap-1">
                                <x-icon name="error-circle" size="16px" class="flex-shrink-0"/>
                                <div>Blocked at {{ format_date($user->signup->blocked_at, 'datetime') }} by {{ $user->signup->blocked_by_user->name ?? 'Unknown' }}</div>
                            </div>

                            <a class="text-xs flex-shrink-0" x-on:click="$dispatch('confirm', {
                                title: 'Unblock User',
                                message: 'Are you sure to unblock this signed-up user?',
                                onConfirmed: () => $wire.unblock(),
                            })">
                                Unblock
                            </a>
                        </div>
                    @elseif ($user->signup->status !== 'trashed')
                        @can('signup.block')
                            <a class="text-red-500 flex items-center gap-1 text-xs font-medium" x-on:click="$dispatch('confirm', {
                                title: 'Block User',
                                message: 'Are you sure to block this signed-up user?',
                                type: 'error',
                                onConfirmed: () => $wire.block(),
                            })">
                                <x-icon name="block" size="16px"/> Block User
                            </a>
                        @endcan
                    @endif
                </div>
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Email</x-slot>
                {{ $user->signup->email ?? $user->email ?? '--' }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Phone</x-slot>
                {{ $user->signup->phone ?? '--' }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Agreed to T&C/Privacy Policy</x-slot>
                {{ $user->signup->agree_tnc ? 'Yes' : 'No' }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Agreed to receiving promotions and marketing</x-slot>
                {{ $user->signup->agree_marketing ? 'Yes' : 'No' }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Sign-up Date</x-slot>
                {{ format_date($user->created_at, 'datetime') }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Status</x-slot>
                <x-badge>{{ $user->signup->status }}</x-badge>
            </x-input.field>
        </div>
    </x-box>
</div>