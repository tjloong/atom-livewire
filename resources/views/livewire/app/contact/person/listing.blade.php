<div class="max-w-screen-xl mx-auto w-full">
    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-button size="sm" color="gray" label="New Person"
                    :href="route('app.contact.person.create', [$contact->id])"
                />
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>

