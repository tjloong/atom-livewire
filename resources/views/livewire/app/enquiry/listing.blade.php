<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Enquiries"/>

    <x-table :data="$this->enquiries->items()">
        <x-slot:header>
            <x-table.searchbar :total="$this->enquiries->total()">
                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->enquiries->links() !!}
</div>