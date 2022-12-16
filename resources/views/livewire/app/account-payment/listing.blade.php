<div class="w-full">
    @if ($fullpage)
        <x-page-header :title="$this->title"/>
    @endif

    <x-table :data="$this->payments->items()">
        <x-slot:header>
            @if (!$fullpage) <x-table.header label="Payment History"/> @endif
            <x-table.searchbar :total="$this->payments->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->payments->links() !!}
</div>
