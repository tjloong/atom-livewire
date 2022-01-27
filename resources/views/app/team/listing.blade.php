<div class="max-w-screen-md mx-auto">
    @if ($fullmode)
        <x-page-header title="Teams">
            <x-button icon="plus" href="{{ route('team.create') }}">
                New Team
            </x-button>
        </x-page-header>
    @endif

    <x-table :total="$teams->total()" :links="$teams->links()">
        <x-slot name="head">
            <x-table.head sort="name">Name</x-table.head>
            <x-table.head align="right">Members</x-table.head>

            @if ($user)
                <x-table.head/>
            @endif
        </x-slot>

        <x-slot name="body">
        @foreach ($teams as $team)
            <x-table.row>
                <x-table.cell>
                    @can('team.manage')
                        <a href="{{ route('team.update', [$team->id]) }}">
                            {{ $team->name }}
                        </a>
                    @else
                        <div class="font-medium">
                            {{ $team->name }}
                        </div>
                    @endcan
                </x-table.cell>
                
                <x-table.cell class="text-right">
                    {{ $team->users_count }} {{ Illuminate\Support\Str::of('member')->plural($team->users_count) }}
                </x-table.cell>

                @if ($user)
                    <x-table.cell width="50">
                        <x-table.button 
                            color="red" 
                            icon="x-circle" 
                            tooltip="Leave Team"
                            wire:click="$emitUp('leaveTeam', {{ $team->id }})"
                        />
                    </x-table.cell>
                @endif
            </x-table.row>
        @endforeach
        </x-slot>
    </x-table>
</div>