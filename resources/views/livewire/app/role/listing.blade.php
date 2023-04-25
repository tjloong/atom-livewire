<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Roles">
        <x-button label="New Role" :href="route('app.role.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>