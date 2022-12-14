<x-table :data="$this->prices">
    <x-slot:header>
        <x-table.header label="Plan Prices">
            <x-button size="sm" color="gray"
                label="New Price"
                :href="route('app.plan.price.create', [$plan->id])"
            />
        </x-table.header>
    </x-slot:header>
</x-table>
