<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$user->name" back>
        @if ($user->id !== auth()->id())
            <div class="flex items-center gap-2">
                @if ($user->status === 'trashed')
                    <x-button color="gray" icon="trash-arrow-up" wire:click="restore">
                        {{ __('Restore') }}
                    </x-button>
    
                    <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
                        title: '{{ __('Force Delete User') }}',
                        message: '{{ __('This will permanently delete the user. Are you sure?') }}',
                        type: 'error',
                        onConfirmed: () => $wire.delete(true),
                    })">
                        {{ __('Force Delete') }}
                    </x-button>
                @else
                    @if ($user->status === 'blocked')
                        <x-button color="gray" icon="play" x-on:click="$dispatch('confirm', {
                            title: '{{ __('Unblock User') }}',
                            message: '{{ __('Are you sure to unblock this user?') }}',
                            onConfirmed: () => $wire.unblock(),
                        })">
                            Unblock
                        </x-button>
                    @else
                        <x-button inverted color="red" icon="block" x-on:click="$dispatch('confirm', {
                            title: '{{ __('Block User') }}',
                            message: '{{ __('Are you sure to block this user?') }}',
                            onConfirmed: () => $wire.block(),
                        })">
                            Block
                        </x-button>
                    @endif
    
                    <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                        title: '{{ __('Delete User') }}',
                        message: '{{ __('Are you sure to delete this user?') }}',
                        type: 'error',
                        onConfirmed: () => $wire.delete(),    
                    })">
                        Delete
                    </x-button>
                @endif
            </div>
        @endif
    </x-page-header>

    <div class="grid gap-6">
        @if ($user->account_id !== auth()->user()->account_id)
            <x-box>
                <div class="p-5">
                    <x-input.field>
                        <x-slot:label>{{ __('Account Name') }}</x-slot:label>
                        <div class="text-lg font-bold">{{ $user->account->name }}</div>
                        <div class="font-medium text-gray-500">
                            @if ($email = $user->account->email)
                                {{ $email }}<br>
                            @endif
                            @if ($phone = $user->account->phone)
                                {{ $phone }}<br>
                            @endif
                        </div>
                    </x-input.field>
                </div>
            </x-box>
        @endif
        
        @livewire('atom.app.user.form', ['user' => $user], key($user->id))

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