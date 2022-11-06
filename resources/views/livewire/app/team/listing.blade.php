<x-table>
    <x-slot:header>
        <x-table.header label="Teams">
            <x-button label="New Team" :href="route('app.team.create')" size="sm"/>
        </x-table.header>

        <x-table.searchbar :total="$this->teams->total()"/>
    </x-slot:header>

    <x-slot:thead>
        <x-table.th label="Name" sort="name"/>
        <x-table.th label="Members" class="text-right"/>
    </x-slot:thead>

    @foreach ($this->teams as $team)
        <x-table.tr>
            <x-table.td
                :label="$team->name"
                :href="route('app.team.update', [$team->id])"
                :small="$team->description"
            />
            <x-table.td class="text-right">
                {{ 
                    __(
                        ':count '.str()->plural('member', $team->users_count), 
                        ['count' => $team->users_count]
                    ) 
                }}
            </x-table.td>
        </x-table.tr>
    @endforeach
</x-table>

{!! $this->teams->links() !!}