<div class="max-w-screen-lg mx-auto">
    <x-table :data="$this->roles->items()">
        <x-slot:header>
            <x-table.header label="Roles">
                <x-button size="sm" color="gray"
                    label="New Role"
                    :href="route('app.role.create')"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->roles->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->roles->links() !!}
</div>