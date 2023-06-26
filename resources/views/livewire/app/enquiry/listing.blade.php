<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>