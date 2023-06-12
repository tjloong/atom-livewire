<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Orders">
        {{-- <x-button label="New Order" :href="route('app.order.create')"/> --}}
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            <x-table.toolbar>
                @if ($count = count($checkboxes))
                    <x-table.checkboxes :count="$count"/>
                    <x-button.confirm :label="'Mark ('.$count.') as closed'" inverted
                        title="Mark as closed"
                        message="Are you sure to mark the selected orders as closed?"
                        callback="mark"
                    />
                @else
                    <x-form.select wire:model="filters.status" :options="collect(['paid', 'shipped', 'closed', 'failed', 'pending'])->map(fn($val) => [
                        'value' => $val,
                        'label' => str($val)->headline(),
                    ])" :label="false" placeholder="All Status"/>
                @endif
            </x-table.toolbar>
        </x-slot:header>
    </x-table>
</div>