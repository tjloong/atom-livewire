<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Sign-Ups"/>
    
    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            <x-table.toolbar>
                <x-form.select
                    wire:model="filters.status"
                    :options="collect(['onboarded', 'new'])->map(fn($val) => [
                        'value' => $val,
                        'label' => ucfirst($val),
                    ])"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>