<div>
    <div class="grid divide-y">
        @foreach ($permissions as $module => $actions)
            <div class="py-2 px-4 grid gap-4 md:grid-cols-2 hover:bg-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <x-icon name="lock-alt" size="20px" class="text-gray-400"/>
                        <span class="font-bold">{{ str()->headline($module) }}</span>
                    </div>
                </div>

                <div class="grid gap-2">
                    @foreach ($actions as $action)
                        <div>
                            <a wire:click="toggle('{{ $module }}', '{{ $action['name'] }}')" class="inline-flex items-center gap-2">
                                <x-icon 
                                    size="18px" 
                                    name="{{ $action['is_granted'] ? 'check-circle' : 'x-circle' }}" 
                                    class="{{ $action['is_granted'] ? 'text-green-500' : 'text-red-500' }}"
                                />
                                <div>
                                    <div class="font-medium">{{ str()->headline($action['name']) }}</div>
                                    @if ($user && $action['is_granted'] && $action['is_granted_by_role'])
                                        <div class="text-xs text-gray-500">Granted by role</div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
