<div class="max-w-screen-xl mx-auto">
    @if ($fullpage) <x-page-header :title="$this->title"/> @endif

    <x-table :data="$this->table">
        <x-slot:header>
            @if (!$fullpage) <x-table.header :label="$this->title"/> @endif

            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.export/>
            </x-table.searchbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>