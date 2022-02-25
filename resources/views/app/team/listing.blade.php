<div class="max-w-screen-md mx-auto">
    @if ($showHeader)
        <x-page-header title="Teams">
            <x-button icon="plus" href="{{ route('team.create') }}">
                New Team
            </x-button>
        </x-page-header>
    @endif

    <x-table :total="$teams->total()" :links="$teams->links()">
        <x-slot name="head">
            <x-table head sort="name">Name</x-table>
            <x-table head align="right">Members</x-table>

            @if ($user)
                <x-table head/>
            @endif
        </x-slot>

        <x-slot name="body">
        @foreach ($teams as $team)
            <x-table row>
                <x-table cell>
                    @can('team.manage')
                        <a href="{{ route('team.update', [$team->id]) }}">
                            {{ $team->name }}
                        </a>
                    @else
                        <div class="font-medium">
                            {{ $team->name }}
                        </div>
                    @endcan
                </x-table>
                
                <x-table cell class="text-right">
                    {{ $team->users_count }} {{ str('member')->plural($team->users_count) }}
                </x-table>

                @if ($user)
                    <x-table cell width="50">
                        <x-button 
                            color="red" 
                            icon="x-circle" 
                            tooltip="Leave Team"
                            wire:click="$emitUp('leaveTeam', {{ $team->id }})"
                        />
                    </x-table>
                @endif
            </x-table>
        @endforeach
        </x-slot>
    </x-table>
</div>