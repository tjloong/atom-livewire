<x-modal id="product-modal" header="Select Product" size="md">
    @if ($product)
        <div class="flex flex-col divide-y">
            <x-link wire:click="$set('product', null)" :label="data_get($product, 'name')" icon="arrow-left" class="p-4"/>

            @forelse (data_get($product, 'variants') as $variant)
                <a wire:click="select(@js([
                    'product_id' => data_get($product, 'id'),
                    'product_variant_id' => data_get($variant, 'id'),
                ]))" class="py-3 px-6 flex gap-3 text-gray-800 hover:bg-slate-100">
                    <div class="grow grid">
                        <div class="font-medium truncate">
                            {{ data_get($variant, 'name') }}
                        </div>
                        @if ($code = data_get($variant, 'code'))
                            <div class="text-sm font-medium text-gray-400"># {{ $code }}</div>
                        @endif
                    </div>

                    <div class="shrink-0">
                        @if ($price = data_get($variant, 'price'))
                            <x-badge :label="currency($price, $currency)"/>
                        @else
                            <x-badge :label="'No '.$currency.' Price'"/>
                        @endif
                    </div>
                </a>
            @empty
                <x-empty-state title="No Product Variants" subtitle="The product variant list is empty"/>
            @endforelse
        </div>
    @else
        <div class="flex flex-col divide-y">
            <div class="p-4 flex items-center gap-3">
                <div class="grow">
                    <x-form.text :label="false"
                        wire:model.debounce.400ms="filters.search"
                        prefix="icon:search"
                        placeholder="Search Products"
                        :clear="!empty(data_get($filters, 'search'))"
                    />
                </div>

                @if (has_route('app.product.create'))
                    <div class="shrink-0">
                        <x-button label="New" :href="route('app.product.create')"/>
                    </div>
                @endif
            </div>

            <div class="overflow-auto max-h-[450px] flex flex-col divide-y rounded-b-lg">
                @forelse ($this->products as $product)
                    <a wire:click="select(@js(['product_id' => data_get($product, 'id')]))" class="py-3 px-6 flex gap-3 text-gray-800 hover:bg-slate-100">
                        <div class="grow grid">
                            <div class="font-medium truncate">
                                {{ data_get($product, 'name') }}
                            </div>
                            @if ($code = data_get($product, 'code'))
                                <div class="text-sm font-medium text-gray-400"># {{ $code }}</div>
                            @endif
                        </div>

                        <div class="shrink-0">
                            @if ($price = data_get($product, 'price'))
                                <x-badge :label="currency($price, $currency)"/>
                            @else
                                <x-badge :label="'No '.$currency.' Price'"/>
                            @endif
                        </div>
                    </a>
                @empty
                    <x-empty-state title="No Product" subtitle="The product list is empty."/>
                @endforelse
            </div>
        </div>
    @endif
</x-modal>
