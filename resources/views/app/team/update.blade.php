<div class="max-w-lg mx-auto">
    <x-page-header title="{!! $team->name !!}" back>
        @can('team.manage')
            <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                title: 'Delete Team',
                message: 'Are you sure to delete this team?',
                type: 'error',
                onConfirmed: () => $wire.delete()
            })">
                Delete
            </x-button>
        @endcan
    </x-page-header>

    <div class="grid gap-6">
        @livewire('atom.team.form', ['team' => $team], key($team->id))

        <x-box>
            <x-slot name="header">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="flex-shrink-0" x-tooltip="test">
                        Team Members
                    </div>
    
                    <div>
                        <x-input.picker wire:input="join" getter="getUsersForPicker">
                            <x-slot name="title">Assign User</x-slot>
                            
                            <x-slot name="trigger">
                                <x-button color="gray" size="xs" icon="plus">Assign</x-button>
                            </x-slot>
                    
                            <x-slot name="item">
                                <div class="font-medium truncate" x-text="opt.name"></div>
                                <div class="text-xs text-gray-500" x-text="opt.email"></div>
                            </x-slot>
                        </x-input.picker>
                    </div>
                </div>
            </x-slot>
            
            <div class="p-3">
                <x-input.search placeholder="Search team members"/>
            </div>
    
            <div class="grid divide-y">
                @forelse($users as $user)
                    <div class="py-2 px-4 flex gap-2 hover:bg-gray-100">
                        <a href="{{ route('user.update', [$user]) }}" class="text-gray-800 flex-grow">
                            <div class="font-semibold">
                                {{ $user->name }}
                            </div>
                            <div class="text-xs text-gray-400 font-medium">
                                {{ $user->email }}
                            </div>
                        </a>
    
                        <a x-tooltip="Remove" class="text-red-500 flex-shrink-0" x-on:click="$dispatch('confirm', {
                            title: 'Remove User',
                            message: 'Remove user from team?',
                            type: 'error',
                            onConfirmed: () => $wire.leave({{ $user->id }}),
                        })">
                            <x-icon name="minus-circle"/>
                        </a>
                    </div>
                @empty
                    <x-empty-state title="No team member" subtitle="This team do not have any member"/>
                @endforelse
            </div>
        </x-box>
    </div>
</div>