<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Products">
        <x-button.create 
            label="New Product" 
            :href="route('app.product.create')"
        />
    </x-page-header>

    <x-table :total="$this->products->total()" :links="$this->products->links()">
        <x-slot:toolbar>
            <div class="flex items-center gap-2">
                <x-form.picker
                    wire:model="filters.status"
                    :options="data_get($this->options, 'statuses')"
                    :selected="data_get($filters, 'status')"
                    placeholder="All Status"
                />

                <x-form.picker
                    wire:model="filters.type"
                    :options="data_get($this->options, 'types')"
                    :selected="data_get($filters, 'type')"
                    placeholder="All Product Types"
                />

                <x-form.picker
                    wire:model="filters.product_category"
                    :options="data_get($this->options, 'product_categories')"
                    :selected="data_get($filters, 'product_category')"
                    placeholder="All Categories"
                />
            </div>
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="name" label="Name"/>
            <x-table.th/>
            <x-table.th label="Category"/>
            <x-table.th sort="price" label="Price"/>
            @if ($this->hasSoldColumn)
                <x-table.th sort="sold" label="Sold" class="text-right"/>
            @endif
            <x-table.th sort="updated_at" class="text-right" label="Updated At"/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->products as $product)
                <x-table.tr>
                    <x-table.td :href="route('app.product.update', [$product->id])" :label="$product->name"/>
                    <x-table.td :active="$product->is_active" class="text-right"/>
                    <x-table.td :tags="$product->productCategories->pluck('name')"/>
                    <x-table.td :amount="$product->price"/>
                    @if ($this->hasSoldColumn)
                        <x-table.td :label="$product->sold" class="text-right"/>
                    @endif
                    <x-table.td :date="$product->updated_at" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>