<x-box header="Product Variants">
    <x-slot:header-buttons>
        <x-button.create
            label="New Variant"
            size="sm"
            :href="route('app.product-variant.create', ['productId' => $product->id])"
        />
    </x-slot:header-buttons>

    @if (count($this->productVariants))
        <x-form.sortable 
            wire:sorted="sort" 
            :config="['handle' => '.sort-handle']"
            class="grid divide-y"
        >
            @foreach ($this->productVariants as $productVariant)
                <div 
                    class="flex items-center gap-4 py-2 px-4 hover:bg-gray-100"
                    data-sortable-id="{{ $productVariant->id }}"
                >
                    <div class="shrink-0 cursor-move sort-handle flex text-gray-400">
                        <x-icon name="sort-alt-2" class="m-auto"/>
                    </div>

                    <div class="shrink-0">
                        <figure class="w-8 h-8 bg-gray-100 rounded shadow overflow-hidden">
                            @if ($url = optional($productVariant->image)->url)
                                <img src="{{ $url }}" class="w-full h-full object-cover">
                            @endif
                        </figure>
                    </div>

                    <div class="grow">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('app.product-variant.update', [$productVariant->id]) }}" class="grow">
                                {{ $productVariant->name }}
                            </a>

                            <div class="flex items-center gap-2">
                                @if ($productVariant->is_default)
                                    <x-badge label="default"/>
                                @endif
                                <x-badge :label="$productVariant->is_active ? 'active' : 'inactive'"/>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0">
                        {{ currency($productVariant->price) }}
                    </div>
                </div>
            @endforeach
        </x-form.sortable>
    @else
        <x-empty-state title="No product variants" subtitle=""/>
    @endif
</x-box>
