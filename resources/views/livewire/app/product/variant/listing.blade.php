<x-box header="Variants">
    @if (count($this->variants))
        <div class="max-w-[500px] overflow-auto">
            <x-form.sortable wire:sorted="sort" class="flex flex-col divide-y">
                @foreach ($this->variants as $variant)
                    <a 
                        href="{{ route('app.product.variant.update', [$variant->id]) }}"
                        class="flex items-center gap-4 py-2 px-4 text-gray-800 hover:bg-slate-100"
                        data-sortable-id="{{ $variant->id }}"
                    >
                        <div class="grow flex flex-col">
                            <div class="font-medium truncate">
                                {{ $variant->name }}
                            </div>
    
                            @if ($code = $variant->code)
                                <div class="text-sm text-gray-400 font-medium">
                                    {{ $code }}
                                </div>
                            @endif
                        </div>
    
                        <div class="shrink-0">
                            @if (!$variant->is_active)
                                <x-badge label="inactive" size="xs"/>
                            @elseif ($variant->is_default)
                                <x-badge label="default" size="xs"/>
                            @endif
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
                    </a>
                @endforeach
            </x-form.sortable>
        </div>
    @endif

    <x-slot:foot>
        <x-button block
            label="New Variant"
            :href="route('app.product.variant.create', [$product->id])"
        />
    </x-slot:foot>
</x-box>
