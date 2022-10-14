<x-table 
    header="Teams"
    :total="$this->teams->total()" 
    :links="$this->teams->links()"
>
    <x-slot:header-buttons>
        <x-button label="New Team" :href="route('app.team.create')" size="sm"/>
    </x-slot:header-buttons>

    <x-slot:head>
        <x-table.th label="Name" sort="name"/>
        <x-table.th label="Members" class="text-right"/>
    </x-slot:head>

    <x-slot:body>
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
    </x-slot:body>
</x-table>
