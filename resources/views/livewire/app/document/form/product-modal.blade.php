<x-modal uid="product-modal" header="Select Product" class="max-w-screen-md">
    @if ($product)
        <div class="-m-6 flex flex-col divide-y">
            <a wire:click="$set('product', null)" class="p-4 flex items-center gap-3 text-gray-800">
                <x-icon name="arrow-left"/> {{ data_get($product, 'name') }}
            </a>

            @forelse (data_get($product, 'variants') as $variant)
                <a 
                    wire:click="select(@js([
                        'product_id' => data_get($product, 'id'),
                        'product_variant_id' => data_get($variant, 'id'),
                    ]))"
                    class="py-3 px-6 flex gap-3 text-gray-800 hover:bg-slate-100"
                >
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
        <div class="-m-6 flex flex-col divide-y">
            <div class="p-4">
                <x-form.text
                    wire:model.debounce.400ms="filters.search"
                    prefix="icon:search"
                    placeholder="Search Products"
                    :clear="!empty(data_get($filters, 'search'))"
                />
            </div>

            <div class="overflow-auto max-h-[450px] flex flex-col divide-y">
                @forelse ($this->products as $product)
                    <a 
                        wire:click="select(@js(['product_id' => data_get($product, 'id')]))"
                        class="py-3 px-6 flex gap-3 text-gray-800 hover:bg-slate-100"
                    >
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

    @if (Route::has('app.product.create'))
        <x-slot:foot>
            <a href="{{ route('app.product.create') }}" class="flex items-center justify-center gap-2">
                <x-icon name="add"/> {{ __('New Product') }}
            </a>
        </x-slot:foot>
    @endif
</x-modal>
