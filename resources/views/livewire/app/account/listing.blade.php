<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :data="$this->accounts->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->accounts->total()">
                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->accounts->links() !!}
</div>