<x-box :header="$this->title">
    @if ($this->isAdmin) 
        <div class="p-4">
            <x-alert>{{ __('Administrator has all the permissions.') }}</x-alert>
        </div>
    @else
        <div class="flex flex-col divide-y">
            @foreach ($this->permissions as $module => $actions)
                <div class="p-4 grid gap-4 md:grid-cols-3">
                    <div class="font-semibold text-gray-600">
                        {{ str()->headline($module) }}
                    </div>

                    <div class="md:col-span-2">
                        @foreach ($actions as $action)
                            <div class="py-1 px-2 rounded hover:bg-slate-100">
                                <a 
                                    wire:click="toggle(@js($module), @js(data_get($action, 'name')))" 
                                    class="flex items-center gap-2"
                                >
                                    <x-icon 
                                        size="15px" 
                                        name="{{ data_get($action, 'is_granted') ? 'circle-check' : 'circle-xmark' }}" 
                                        class="{{ data_get($action, 'is_granted') ? 'text-green-500' : 'text-red-500' }}"
                                    />

                                    <div class="font-medium">{{ str()->headline($action['name']) }}</div>
                                </a>
                                
                                @if ($user && data_get($action, 'is_granted') && data_get($action, 'is_granted_by_role'))
                                    <div class="text-sm text-gray-500">
                                        {{ __('Granted by role') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-box>