<x-modal uid="permission-form-modal" icon="lock" header="Edit Permissions" class="max-w-screen-sm">
    <div class="flex flex-col gap-6">
        <x-box>
            <div class="p-3">
                @if ($user)
                    <x-form.field label="User">
                        <div class="flex items-center gap-2">
                            {{ $user->name }} 
                            @if ($role = $user->role) 
                                <span class="text-sm text-gray-500 font-medium">({{ $role->name }})</span> 
                            @endif
                        </div>
                        <div class="text-sm text-gray-500 font-medium">{{ $user->email }}</div>
                    </x-form.field>
                @elseif ($role)
                    <x-form.field label="Role">
                        {{ $role->name }}
                    </x-form.field>
                @endif
            </div>
        </x-box>

        @if ($this->isAdmin)
            <x-alert>
                {{ __('Administrator has all the permissions.') }}
            </x-alert>
        @else
            <div class="flex flex-col gap-4">
                @foreach ($this->permissions as $module => $actions)
                    <div class="bg-gray-100 rounded-lg p-3 grid gap-4 md:grid-cols-3">
                        <div class="font-semibold text-gray-600">
                            {{ str()->headline($module) }}
                        </div>
    
                        <div class="md:col-span-2">
                            <div class="grid gap-2">
                                @foreach ($actions as $action)
                                    <a 
                                        wire:click="toggle(@js($module), @js(data_get($action, 'name')))" 
                                        class="flex items-center gap-2"
                                    >
                                        <x-icon 
                                            size="15px" 
                                            name="{{ data_get($action, 'is_granted') ? 'circle-check' : 'circle-xmark' }}" 
                                            class="{{ data_get($action, 'is_granted') ? 'text-green-500' : 'text-red-500' }}"
                                        />
    
                                        <div>
                                            <div class="font-medium">{{ str()->headline($action['name']) }}</div>
                                            @if ($user && data_get($action, 'is_granted') && data_get($action, 'is_granted_by_role'))
                                                <div class="text-sm text-gray-500">
                                                    {{ __('Granted by role') }}
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-modal>