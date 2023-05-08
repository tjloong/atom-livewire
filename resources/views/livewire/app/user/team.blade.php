@if (!enabled_module('teams') || tier('root'))
    <template></template>
@else
    <x-box header="Teams">
        @if ($this->teams->count())
            <x-slot:buttons>
                <x-button label="New Team" :href="route('app.team.create')" size="sm" color="gray"/>
            </x-slot:buttons>

            <div class="flex flex-col divide-y max-h-[450px] overflow-auto">
                @foreach ($this->teams as $team)
                    <div wire:click="toggle(@js($team->id))" class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-100">
                        <div class="shrink-0 flex items-center justify-center">
                            @if ($team->users()->find($user->id)) <x-icon name="circle-check" class="text-green-500"/>
                            @else <x-icon name="circle-minus" class="text-gray-400"/>
                            @endif
                        </div>

                        <div class="grow">
                            {{ $team->name }}<br>
                            <div class="text-sm text-gray-500">{{ $team->description }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state title="No Teams" subtitle="You have not setup any teams.">
                <x-button label="Create Team" :href="route('app.team.create')"/>
            </x-empty-state>
        @endif
    </x-box>
@endif