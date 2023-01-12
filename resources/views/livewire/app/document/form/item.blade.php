<div class="flex flex-col gap-4 md:flex-row">
    <div class="grow flex flex-col gap-4">
        @if ($productId = data_get($item, 'product_id'))
            <div class="flex items-center justify-between gap-3">
                <a 
                    href="{{ route('app.product.update', [$productId]) }}" 
                    target="_blank" 
                    class="flex items-center gap-2"
                >
                    {{ data_get($item, 'name') }} <x-icon name="open" size="10" class="text-gray-500"/>
                </a>

                <x-close wire:click="clearProduct"/>
            </div>
        @else
            <x-form.text :placeholder="$columns->get('item_name')"
                wire:model.debounce.300ms="item.name"
            >
                <x-slot:button 
                    icon="cube" 
                    label="Product"
                    wire:click="open"
                ></x-slot:button>
            </x-form.text>
        @endif

        @if ($columns->get('item_description'))
            <x-form.textarea :placeholder="$columns->get('item_description')"
                wire:model.debounce.300ms="item.description"
            />
        @endif
    </div>

    <div class="shrink-0 flex flex-col gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            @if ($columns->get('qty'))
                <div class="md:w-40">
                    <x-form.number :placeholder="$columns->get('qty')"
                        wire:model.debounce.300ms="item.qty"
                        class="text-right"
                    />
                </div>
            @endif

            @if ($columns->get('price'))
                <div class="md:w-40">
                    <x-form.number :placeholder="$columns->get('price')"
                        wire:model.debounce.300ms="item.amount"
                        class="text-right"
                    />
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

        @if ($columns->has('tax'))
            <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
                @if (count($this->taxes) || count(data_get($item, 'taxes', [])))
                    <div class="p-3">
                        <x-form.field :label="$columns->get('tax')">
                            <div class="flex flex-col gap-2">
                                @foreach (data_get($item, 'taxes', []) as $tax)
                                    <div class="p-1 flex items-center gap-2">
                                        <a wire:click="removeTax(@js(data_get($tax, 'id')))" class="text-red-500 flex">
                                            <x-icon name="remove" class="m-auto"/>
                                        </a>
                                        <div class="grow">{{ data_get($tax, 'label') }}</div>
                                        <div class="shrink-0">{{ currency(data_get($tax, 'amount')) }}</div>
                                    </div>                                                
                                @endforeach

                                @if (count($this->taxes))
                                    <x-form.select 
                                        :placeholder="'Select '.$columns->get('tax')"
                                        :options="$this->taxes"
                                        x-on:input="$wire.call('addTax', $event.detail).then(() => value = null)"
                                    />
                                @endif
                            </div>
                        </x-form.field>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="shrink-0 md:w-10 md:flex md:justify-center">
        <a
            wire:click="$emitUp(
                'removeItem', 
                @js(data_get($item, 'ulid') ?? data_get($item, 'id'))
            )"
            class="text-red-500 mt-2 flex items-center gap-2 md:block"
        >
            <x-icon name="remove"/>
            <div class="md:hidden">{{ __('Remove') }}</div>
        </a>
    </div>
</div>
