<div class="flex flex-col gap-4 md:flex-row">
    <div class="grow flex flex-col gap-4">
        @if (enabled_module('products') && ($productId = data_get($item, 'product_id')))
            <div class="flex items-center justify-between gap-3">
                <x-link :href="route('app.product.update', [$productId])" :label="data_get($item, 'name')" target="_blank" icon="open" class="grow"/>
                <x-close wire:click="clearProduct"/>
            </div>
        @else
            <x-form.text wire:model.debounce.300ms="item.name" :placeholder="$columns->get('item_name')" :label="false">
                @module('products')
                    <x-slot:button wire:click="open" label="Product" icon="cube"></x-slot:button>
                @endmodule
            </x-form.text>
        @endif

        @if ($columns->get('item_description'))
            <x-form.textarea wire:model.debounce.300ms="item.description" :placeholder="$columns->get('item_description')" :label="false"/>
        @endif
    </div>

    <div class="shrink-0 flex flex-col gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            @if ($columns->get('qty'))
                <div class="md:w-40">
                    <x-form.number wire:model.debounce.300ms="item.qty" :placeholder="$columns->get('qty')" class="text-right" :label="false" step=".01"/>
                </div>
            @endif

            @if ($columns->get('price'))
                <div class="md:w-40">
                    <x-form.number wire:model.debounce.300ms="item.amount" :placeholder="$columns->get('price')" class="text-right" :label="false" step=".01"/>
                </div>
            @endif

            @if ($columns->get('total'))
                <div class="md:w-40 py-2 flex items-center justify-end gap-1">
                    {{ number_format($this->subtotal, 2) }}
                </div>
            @endif
        </div>

        @if ($columns->get('price'))
            @if (
                (float) $this->recommendedPrice > 0
                && (float) data_get($item, 'amount') !== (float) $this->recommendedPrice
            )
                <div class="bg-yellow-100 text-sm py-2 px-4 rounded-lg border border-yellow-200 flex items-center gap-2">
                    <x-icon name="circle-info" size="12" class="shrink-0 text-yellow-500"/>
                    <div class="grow text-yellow-800 font-medium">
                        {{ __('Recommended '.strtolower($columns->get('price'))) }}:
                        {{ currency($this->recommendedPrice) }}
                    </div>
                    <a wire:click="$set('item.amount', @js($this->recommendedPrice))" class="shrink-0">
                        {{ __('Use') }}
                    </a>
                </div>
            @endif
        @endif

        @if (enabled_module('taxes') && $columns->has('tax'))
            <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
                @if (count($this->taxes) || data_get($item, 'taxes'))
                    <div class="p-4">
                        <div class="flex flex-col gap-2">
                            @foreach ($this->taxes as $val)
                                <div class="flex items-center gap-3">
                                    @if ($addedTax = collect(data_get($item, 'taxes'))->firstWhere('id', $val->id))
                                        <a wire:click="removeTax({{ $val->id }})" class="grow flex items-center gap-3">
                                            <x-icon name="circle-check" class="text-green-500 shrink-0"/>
                                            <div class="grow text-sm">{{ $val->label }}</div>
                                        </a>
                                        <div class="shrink-0 text-sm">
                                            {{ currency(data_get($addedTax, 'amount')) }}
                                        </div>
                                    @else
                                        <a wire:click="addTax({{ $val->id }})" class="grow flex items-center gap-3">
                                            <x-icon name="circle-xmark" class="text-gray-400 shrink-0"/>
                                            <div class="grow text-sm">{{ $val->label }}</div>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="shrink-0 md:w-10 md:flex md:justify-center">
        <a wire:click="$emitUp(
            'removeItem', 
            @js(data_get($item, 'ulid') ?? data_get($item, 'id'))
        )" class="text-red-500 mt-2 flex items-center gap-2 md:block">
            <x-icon name="remove"/>
            <div class="md:hidden">{{ __('Remove') }}</div>
        </a>
    </div>
</div>
