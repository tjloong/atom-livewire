<div class="max-w-screen-xl mx-auto w-full">
    <x-table :data="$this->paginator->items()">
        <x-slot:header>
            <x-table.header label="Plan Prices">
                <x-button size="sm" color="gray"
                    label="New Price"
                    :href="route('app.plan.price.create', [$plan->id])"
                />
            </x-table.header>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>
