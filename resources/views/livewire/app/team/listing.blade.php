<div class="max-w-screen-lg mx-auto w-full">
    <x-page-header title="Teams">
        <x-button label="New Team" :href="route('app.team.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>
    
    {!! $this->paginator->links() !!}
</div>