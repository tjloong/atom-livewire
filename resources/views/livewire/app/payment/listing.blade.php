<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Payments">
        {{-- <x-button label="New Order" :href="route('app.order.create')"/> --}}
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            <x-table.toolbar>
                <x-form.select wire:model="filters.status" :options="collect(['success', 'failed', 'draft', 'pending'])->map(fn($val) => [
                    'value' => $val,
                    'label' => str($val)->headline(),
                ])" :label="false" placeholder="All Status"/>
            </x-table.toolbar>
        </x-slot:header>
    </x-table>
</div>