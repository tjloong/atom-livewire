<div class="max-w-screen-lg mx-auto w-full">
    <x-table>
        <x-slot:header>
            <x-table.header label="Teams">
                <x-button size="sm" color="gray"
                    label="New Team" 
                    wire:click="open('create')"
                />
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
                    :small="$team->description"
                    wire:click="open('edit', {{ $team->id }})"
                />

                <x-table.td class="text-right">
                    <a wire:click="open('user', { team_id: {{ $team->id }} })">
                        {{ 
                            __(
                                ':count '.str()->plural('member', $team->users_count), 
                                ['count' => $team->users_count]
                            ) 
                        }}
                    </a>
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table>
    
    {!! $this->teams->links() !!}

    @livewire(lw('app.settings.system.team-form-modal'), key('team-form'))
    @livewire(lw('app.settings.system.user-drawer'), key('user-drawer'))
</div>