<div>
    <div class="grid divide-y">
        @foreach ($groups as $group)
            <div wire:click="$set('selectedGroup', @js($group))" class="flex cursor-pointer py-2 px-4 hover:bg-gray-100">
                <div class="flex-shrink-0 w-40">
                    <div class="font-medium text-gray-800">{{ $group['name'] }}</div>
                    @if (collect($group['abilities'])->where('overwrote', true)->count())
                        <div class="mt-1 text-xs font-medium italic text-gray-500">Overwrote</div>
                    @endif
                </div>
    
                <div class="flex flex-wrap items-center space-x-2">
                    @foreach ($group['abilities'] as $index => $ability)
                        <div class="flex items-center space-x-1">
                            @if ($index > 0) <div class="mx-2">&bull;</div> @endif
    
                            @if ($ability['enabled']) <x-icon name="check" class="text-green-500"/>
                            @else <x-icon name="x" class="text-red-500"/>
                            @endif
                            
                            <div class="text-xs capitalize {{ $ability['enabled'] ? 'text-green-500' : 'font-medium text-gray-500' }}">
                                {{ $ability['name'] }}
                            </div>
                        </div>                    
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <x-modal>
        <x-slot name="title">
            Update Permissions
        </x-slot>

        <div>
            @if ($selectedGroup)
                <x-input.field>
                    <x-slot name="label">Module</x-slot>
                    {{ $selectedGroup['name'] }}
                </x-input.field>
            
                <x-input.field>
                    <x-slot name="label">Permissions</x-slot>
                    <div class="grid space-y-1">
                        @foreach ($selectedGroup['abilities'] as $index => $ability)
                            <x-input.checkbox
                                :checked="$ability['enabled']"
                                x-on:input="$wire.save({{ json_encode($ability) }})"
                            >
                                <span class="capitalize">{{ $ability['name'] }}</span>
                            </x-input.checkbox>
                        @endforeach
                    </div>
                </x-input.field>

                @if ($selectedGroup['is_overwrote'])
                    <x-button
                        wire:click="resetOverwrote({{ collect($selectedGroup['abilities'])->pluck('id')->toJson() }})"
                        color="gray"
                    >
                        Reset
                    </x-button>
                @endif
            @endif
        </div>
    </x-modal>
</div>
