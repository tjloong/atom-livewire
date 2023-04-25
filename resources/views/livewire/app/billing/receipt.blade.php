<x-table :data="$this->table">
    <x-slot:header>
        <x-table.header label="Receipts"/>
    </x-slot:header>
</x-table>

{!! $this->paginator->links() !!}