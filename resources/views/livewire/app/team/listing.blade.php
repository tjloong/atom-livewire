<div class="max-w-screen-lg mx-auto w-full">
    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.header label="Teams">
                <x-button size="sm" color="gray" label="New Team" :href="route('app.team.create')"/>
            </x-table.header>
    
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>
    
    {!! $this->paginator->links() !!}
</div>