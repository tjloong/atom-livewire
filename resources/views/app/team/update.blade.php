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

    @livewire('atom.team.form', ['team' => $team], key($team->id))

    <x-box>
        <x-slot name="header">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex-shrink-0">
                    Team Members
                </div>

                <x-input.picker wire:input="assignUser" getter="getAssignableUsers" title="Assign User">
                    <x-slot name="trigger">
                        <a class="text-xs flex items-center space-x-1">
                            <x-icon name="plus" size="16px"/> Assign
                        </a>
                    </x-slot>
            
                    <x-slot name="item">
                        <div class="font-medium truncate" x-text="opt.name"></div>
                        <div class="text-xs text-gray-500" x-text="opt.email"></div>
                    </x-slot>
                </x-input.picker>
            </div>
        </x-slot>
        
        <div class="p-3">
            <x-input.search placeholder="Search team members"/>
        </div>

        <div class="grid divide-y max-h-96 overflow-auto">
            @forelse($users as $user)
                <div class="py-2 px-4 flex space-x-2 hover:bg-gray-100">
                    <a href="{{ route('user.update', [$user]) }}" class="text-gray-800 flex-grow">
                        <div class="font-semibold">
                            {{ $user->name }}
                        </div>
                        <div class="text-xs text-gray-400 font-medium">
                            {{ $user->email }}
                        </div>
                    </a>

                    <a x-tooltip="Remove" wire:click="removeUser({{ $user->id }})" class="text-red-500 flex-shrink-0">
                        <x-icon name="minus-circle"/>
                    </a>
                </div>
            @empty
                <x-empty-state title="No team member" subtitle="This team do not have any member"/>
            @endforelse
        </div>
    </x-box>
</div>