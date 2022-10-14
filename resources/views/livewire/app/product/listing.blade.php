<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Products">
        <x-button label="New Product" :href="route('app.product.create')"/>
    </x-page-header>

    <x-table :total="$this->products->total()" :links="$this->products->links()">
        <x-slot:toolbar>
            <div class="flex items-center gap-2">
                <x-form.select
                    wire:model="filters.status"
                    :options="data_get($this->options, 'statuses')"
                    placeholder="All Status"
                />

                <x-form.select
                    wire:model="filters.type"
                    :options="data_get($this->options, 'types')"
                    placeholder="All Product Types"
                />

                <x-form.select
                    wire:model="filters.product_category"
                    :options="data_get($this->options, 'product_categories')"
                    placeholder="All Categories"
                />
            </div>
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="code" label="Code"/>
            <x-table.th sort="name" label="Name"/>
            <x-table.th label="Category"/>
            <x-table.th sort="price" label="Price" class="text-right"/>
            @if ($this->hasSoldColumn)
                <x-table.th sort="sold" label="Sold" class="text-right"/>
            @endif
            <x-table.th/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->products as $product)
                <x-table.tr>
                    <x-table.td
                        :href="route('app.product.update', [$product->id])"
                        :label="$product->code"
                    />
                    
                    <x-table.td
                        :href="route('app.product.update', [$product->id])"
                        :label="$product->name"
                        :small="
                            $product->type === 'variant'
                                ? __(
                                    ':count '.str()->plural('variant', $product->variants->count()),
                                    ['count' => $product->variants->count()]
                                )
                                : null
                        "
                    />

                    <x-table.td :tags="$product->categories->pluck('name.'.app()->currentLocale())"/>
                    
                    @if (is_numeric($product->price))
                        <x-table.td :amount="$product->price" class="text-right"/>
                    @else
                        <x-table.td
                            :amount="data_get($product->price, 'amount')"
                            :currency="data_get($product->price, 'currency')"
                            class="text-right"
                        />
                    @endif

                    @if ($this->hasSoldColumn)
                        <x-table.td :label="$product->sold" class="text-right"/>
                    @endif

                    <x-table.td :active="$product->is_active" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>