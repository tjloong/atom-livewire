<div class="max-w-screen-xl mx-auto w-full">
    <x-table :data="$this->persons->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->persons->total()">
                <x-button size="sm" color="gray" label="New Person"
                    :href="route('app.contact.person.create', [$contact->id])"
                />
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->persons->links() !!}
</div>

