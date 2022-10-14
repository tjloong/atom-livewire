<x-box header="Product Variants">
    <x-slot:header-buttons>
        <x-button size="sm"
            label="New Variant"
            :href="route('app.product.variant.create', ['productId' => $product->id])"
        />
    </x-slot:header-buttons>

    @if (count($this->variants))
        <x-form.sortable 
            wire:sorted="sort" 
            :config="['handle' => '.sort-handle']"
            class="grid divide-y"
        >
            @foreach ($this->variants as $variant)
                <div 
                    class="flex items-center gap-4 py-2 px-4 hover:bg-gray-100"
                    data-sortable-id="{{ $variant->id }}"
                >
                    <div class="shrink-0 cursor-move sort-handle flex text-gray-400">
                        <x-icon name="sort" class="m-auto"/>
                    </div>

                    <div class="shrink-0">
                        <figure class="w-8 h-8 bg-gray-100 rounded shadow overflow-hidden">
                            @if ($url = optional($variant->image)->url)
                                <img src="{{ $url }}" class="w-full h-full object-cover">
                            @endif
                        </figure>
                    </div>

                    <div class="grow">
                        <div class="flex items-center justify-between gap-2">
                            <div class="grid">
                                <a href="{{ route('app.product.variant.update', [
                                    'productId' => $product->id,
                                    'variantId' => $variant->id,
                                ]) }}" class="grow">
                                    {{ $variant->name }}
                                </a>

                                @if ($code = $variant->code)
                                    <div class="text-sm text-gray-400 font-medium">
                                        {{ $code }}
                                    </div>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if ($variant->is_default)
                                    <x-badge label="default"/>
                                @endif
                                <x-badge :label="$variant->is_active ? 'active' : 'inactive'"/>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0">
                        @if (is_numeric($variant->price)) 
                            {{ currency($variant->price) }}
                        @else
                            {{ currency(
                                data_get($variant->price, 'amount'), 
                                data_get($variant->price, 'currency')
                            ) }}
                        @endif
                    </div>
                </div>
            @endforeach
        </x-form.sortable>
    @else
        <x-empty-state title="No product variants" subtitle="This product do not have any variants."/>
    @endif
</x-box>
