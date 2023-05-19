<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Taxes">
        <x-button label="New Tax" :href="route('app.tax.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>
