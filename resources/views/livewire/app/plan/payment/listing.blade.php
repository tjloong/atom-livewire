<div class="max-w-screen-xl mx-auto w-full">
    @if ($fullpage) <x-page-header :title="$this->title"/> @endif

    <x-table :data="$this->table">
        <x-slot:header>
            @if (!$fullpage) <x-table.header :label="$this->title"/> @endif
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>
