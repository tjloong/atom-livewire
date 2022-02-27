<div class="max-w-lg mx-auto">
    <x-page-header :title="$user->name" back>
        @if ($user->id !== auth()->id())
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

    @livewire('atom.user.form', ['user' => $user], key($user->id))

    @module('permissions')
        <x-box>
            <x-slot name="header">User's Permissions</x-slot>
            @livewire('atom.permission.listing', ['user' => $user], key('permissions'))
        </x-box>
    @endmodule
</div>