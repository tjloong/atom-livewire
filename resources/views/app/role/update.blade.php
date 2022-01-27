<div class="max-w-lg mx-auto">
    <x-page-header title="{!! $role->name !!}" back>
        @if (!$readonly)
            <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
                title: 'Delete Role',
                message: 'Are you sure to delete this role?',
                type: 'error',
                onConfirmed: () => $wire.delete(),    
            })">
                Delete
            </x-button>
        @endif
    </x-page-header>

    @if ($readonly)
        <x-box>
            <div class="p-5">
                <x-input.field>
                    <x-slot name="label">Role Name</x-slot>
                    {{ $role->name }}
                </x-input.field>

                @if ($role->is_system)
                    <x-alert>This role is a system default role.</x-alert>
                @endif
            </div>
        </x-box>
    @else
        <form wire:submit.prevent="save">
            <x-box>
                <div class="p-5">
                    <x-input.text wire:model.defer="role.name" required>
                        Role Name
                    </x-input.text>
                </div>
    
                <x-slot name="buttons">
                    <div class="flex justify-between">
                        <x-button type="submit" icon="check" color="green">
                            Save
                        </x-button>

                        <x-button icon="copy" color="gray" wire:click="duplicate">
                            Duplicate
                        </x-button>
                    </div>
                </x-slot>
            </x-box>
        </form>
    @endif

    <x-box>
        <x-slot name="header">Permitted Features Access</x-slot>

        @if ($role->slug === 'administrator' && $role->is_system)
            <div class="p-5">
                <x-alert>Administrator can access all features in the system.</x-alert>
            </div>
        @elseif ($role->is_root)
            <div class="p-5">
                <x-alert>Root can access everything in the system.</x-alert>
            </div>
        @else
            @livewire('atom.ability.listing', ['role' => $role], key($role->id))
        @endif
    </x-box>

    @if ($readonly)
        <x-box>
            <x-slot name="header">Data Scope</x-slot>

            <div class="p-5">
                <div class="flex space-x-2">
                    <x-icon name="check-circle" class="text-green-500"/>
                    <div>
                        <div class="font-semibold">{{ $role->scope_description['label'] }}</div>
                        <div class="text-gray-500">{{ $role->scope_description['caption'] }}</div>
                    </div>
                </div>
            </div>
        </x-box>
    @else
        <form wire:submit.prevent="save">
            <x-box>
                <x-slot name="header">Data Scope</x-slot>
    
                <div class="p-5">
                    <div class="flex flex-col space-y-2">
                        @foreach ($scopes as $scope)
                            <x-input.radio name="scope" wire:model.defer="role.scope" :value="$scope['name']" :checked="$role->scope === $scope['name']">
                                <div>
                                    <div class="font-medium">
                                        {{ $scope['label'] }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $scope['caption'] }}
                                    </div>
                                </div>
                            </x-input.radio>
                        @endforeach
                    </div>
                </div>
    
                <x-slot name="buttons">
                    <x-button type="submit" icon="check" color="green">
                        Save
                    </x-button>
                </x-slot>
            </x-box>
        </form>
    @endif

    <x-box>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <div>Users</div>
                <x-input.picker wire:input="assignUser" getter="getAssignableUsers" title="Assign User">
                    <x-slot name="trigger">
                        <a class="text-xs text-theme flex items-center">
                            <x-icon name="plus" size="18px"/> Assign
                        </a>
                    </x-slot>
    
                    <x-slot name="item">
                        <div class="font-medium" x-text="opt.name"></div>
                        <div class="text-xs text-gray-500 font-normal" x-text="opt.email"></div>
                    </x-slot>
                </x-input.picker>    
            </div>
        </x-slot>

        <div class="grid divide-y">
            @forelse ($users as $user)
                <a href="{{ route('user.update', [$user]) }}" class="py-2 px-4 hover:bg-gray-100">
                    <div class="font-medium text-gray-800">{{ $user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                </a>
            @empty
                <x-empty-state title="No user" subtitle="This role do not have any users assigned"/>
            @endforelse
        </div>
    </x-box>
</div>