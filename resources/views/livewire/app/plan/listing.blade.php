<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Plans">
        <x-button label="New Plan" :href="route('app.plan.create')"/>
    </x-page-header>

    <x-table :data="$this->plans->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->plans->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->plans->links() !!}
</div>