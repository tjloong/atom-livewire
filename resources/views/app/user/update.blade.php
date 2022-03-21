<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$user->name" back>
        @if ($user->id !== auth()->id())
            @if ($user->status === 'blocked')
                <x-button color="gray" icon="play" x-on:click="$dispatch('confirm', {
                    title: 'Unblock User',
                    message: 'Are you sure to unblock this user?',
                    onConfirmed: () => $wire.unblock(),
                })">
                    Unblock
                </x-button>
            @else
                <x-button inverted color="red" icon="block" x-on:click="$dispatch('confirm', {
                    title: 'Block User',
                    message: 'Are you sure to block this user?',
                    onConfirmed: () => $wire.block(),
                })">
                    Block
                </x-button>
            @endif

            <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                title: 'Delete User',
                message: 'Are you sure to delete this user?',
                type: 'error',
                onConfirmed: () => $wire.delete(),    
            })">
                Delete
            </x-button>
        @endif
    </x-page-header>

    <div class="grid gap-6">
        @livewire('atom.user.form', ['user' => $user], key($user->id))

        @module('permissions')
            @if (!$user->is_root && $user->is_active)
                <x-box>
                    <x-slot name="header">User's Permissions</x-slot>
                    @livewire('atom.permission.listing', ['user' => $user], key('permissions'))
                </x-box>
            @endif
        @endmodule
    </div>
</div>