<div class="max-w-lg mx-auto">
    <x-page-header title="{!! $user->name !!}" back>
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

    @feature('abilities')
        <x-box>
            <x-slot name="header">Permitted features granted by role</x-slot>

            @if ($user->isRole('root'))
                <div class="p-5">
                    <x-alert>Root user can access everything in the system.</x-alert>
                </div>
            @elseif ($user->isRole('administrator') && $user->role->is_system)
                <div class="p-5">
                    <x-alert>Administrator can access all modules in the system.</x-alert>
                </div>
            @else
                @livewire('atom.ability.listing', ['user' => $user], key('abilities'))
            @endif
        </x-box>

        <x-box>
            <x-slot name="header">Data scope granted by role</x-slot>
            <div class="flex p-4 space-x-2">
                <x-icon name="check-circle" class="text-green-500"/>
                <div>
                    <div class="font-medium">{{ $user->role->scope_description['label'] }}</div>
                    <div class="text-gray-500">{{ $user->role->scope_description['caption'] }}</div>
                </div>
            </div>
        </x-box>
    @endfeature

    @feature('teams')
        <x-box>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <div>Teams</div>
                    <x-input.picker wire:input="joinTeam" getter="getTeams" title="Join Team">
                        <x-slot name="trigger">
                            <a class="text-xs flex items-center space-x-1">
                                <x-icon name="plus" size="16px"/> Join
                            </a>
                        </x-slot>
                    </x-input.picker>
                </div>
            </x-slot>

            <div class="grid divide-y">
                @forelse ($teams as $team)
                    <div class="flex justify-between py-2 px-4 hover:bg-gray-100">
                        @can('team.manage')
                            <a href="{{ route('team.update', [$team]) }}" class="font-medium flex-grow text-gray-800">
                                {{ $team->name }}
                            </a>
                        @else
                            <div class="font-medium">{{ $team->name }}</div>
                        @endcan

                        <a class="text-red-500" x-tooltip="Remove" wire:click="leaveTeam({{ $team->id }})">
                            <x-icon name="minus-circle"/>
                        </a>
                    </div>
                @empty
                    <x-empty-state title="No Teams" subtitle="This user did not joined any teams"/>
                @endforelse
            </div>
        </x-box>
    @endfeature
</div>