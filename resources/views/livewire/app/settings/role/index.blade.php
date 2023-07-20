<div class="max-w-screen-lg">
    <x-page-header title="Roles">
        <x-button icon="add" label="New Role" wire:click="updateOrCreate"/>
    </x-page-header>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Role" sort="name"/>
            <x-table.th label="Users"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $role)
            <x-table.tr>
                <x-table.td :label="$role->name" wire:click="updateOrCreate({{ $role->id }})"/>
                <x-table.td :count="$role->users_count" uom="user"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}

    @livewire('app.settings.role.form')
</div>