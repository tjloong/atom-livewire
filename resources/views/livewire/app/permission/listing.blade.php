<x-box header="Permissions">
    @if ($role && $role->slug === 'admin')
        <div class="p-5">
            <x-alert>
                {{ __('Administrator has all the permissions.') }}
            </x-alert>
        </div>
    @else
        <div class="grid divide-y">
            @foreach ($this->permissions as $module => $actions)
                <div class="py-2 px-4 grid gap-4 md:grid-cols-3 hover:bg-gray-100">
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
</x-box>
