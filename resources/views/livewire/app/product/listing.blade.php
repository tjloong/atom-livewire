<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Products">
        <x-button label="New Product" :href="route('app.product.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>

            <x-table.toolbar>
                <div class="flex items-center gap-2">
                    <x-form.select wire:model="filters.status" :label="false"
                        :options="data_get($this->options, 'statuses')"
                        placeholder="All Status"
                    />
    
                    <x-form.select wire:model="filters.type" :label="false"
                        :options="data_get($this->options, 'types')"
                        placeholder="All Product Types"
                    />

                    <x-form.select.label wire:model="filters.product_category" :label="false"
                        type="product-category"
                        placeholder="All Categories"
                    />
                </div>
            </x-table.toolbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>