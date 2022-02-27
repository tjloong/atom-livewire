<div class="max-w-screen-md mx-auto">
    <x-page-header title="Teams">
        <x-button icon="plus" href="{{ route('team.create') }}">
            New Team
        </x-button>
    </x-page-header>

    <x-table :total="$teams->total()" :links="$teams->links()">
        <x-slot name="head">
            <x-table head sort="name">Name</x-table>
            <x-table head align="right">Members</x-table>
        </x-slot>

        <x-slot name="body">
            @foreach ($teams as $team)
                <x-table row>
                    <x-table cell>
                        <a href="{{ route('team.update', [$team->id]) }}">
                            {{ $team->name }}
                        </a>
                        <div class="font-medium text-gray-500">
                            {{ $team->description }}
                        </div>
                    </x-table>
                    
                    <x-table cell class="text-right">
                        {{ $team->users_count }} {{ str('member')->plural($team->users_count) }}
                    </x-table>
                </x-table>
            @endforeach
        </x-slot>
    </x-table>
</div>