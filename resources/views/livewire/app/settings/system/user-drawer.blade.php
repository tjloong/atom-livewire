<div>

    <x-drawer uid="user-drawer">
        <x-slot:header>
            <div class="flex items-center justify-between gap-3 p-4">
                <div class="grid">
                    @if ($role)
                        <div class="text-xs font-medium text-gray-500">
                            {{ __('ROLE') }}
                        </div>
                        <div class="text-lg font-bold">
                            {{ $role->name }}
                        </div>
                    @elseif ($team)
                        <div class="text-xs font-medium text-gray-500">
                            {{ __('TEAM') }}
                        </div>
                        <div class="text-lg font-bold">
                            {{ $team->name }}
                        </div>
                    @endif 
                </div>
                <x-close x-on:click="close()"/>
            </div>
        </x-slot:header>

        @if ($count = count($this->users))
            <div class="-mx-6 -mt-6 mb-6 px-4 py-2 border-b text-sm text-gray-500 font-medium">
                {{ __('Total :count '.str('user')->plural($count), [
                    'count' => $count,
                ]) }}
            </div>
        @endif
    
        <div class="flex flex-col gap-3">
            @forelse ($this->users as $user)
                @php $self = $user->id === auth()->user()->id @endphp
                <x-box class="rounded-lg">
                    <div class="flex justify-between gap-3 p-3 hover:bg-slate-100">
                        <div class="grid">
                            @if ($self) 
                                <div class="flex items-center gap-2">
                                    <div class="truncate">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 font-medium">
                                        ({{ __('You') }})
                                    </div>
                                </div>
                            @else 
                                <a wire:click="edit({{ $user->id }})" class="text-blue-500 truncate">
                                    {{ $user->name }}
                                </a>
                            @endif
    
                            @if ($role = $user->role)
                                <div class="text-sm text-gray-500 font-medium">
                                    {{ $role->name }}
                                </div>
                            @endif
                            
                            <div class="text-sm text-gray-500 font-medium">
                                {{ $user->email }}
                            </div>
                        </div>
    
                        <div>
                            <x-badge :label="$user->status"/>
                        </div>
                    </div>
                </x-box>
            @empty
                <x-empty-state title="No User" subtitle="User list is empty"/>
            @endforelse
        </div>
    </x-drawer>

    @livewire(lw('app.settings.system.user-form-modal'))
</div>