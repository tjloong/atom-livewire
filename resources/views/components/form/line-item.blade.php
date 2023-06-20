@props([
    'model' => $attributes->wire('model')->value(),
    'columns' => $attributes->get('columns', [
        'item_name' => 'Item Name',
        'item_description' => 'Item Description',
        'qty' => 'Qty',
        'amount' => 'Amount',
        'total' => 'Total',
    ]),
])

@props([
    'id' => data_get($this, "$model.ulid") ?? data_get($this, "$model.id")
])

<div {{ $attributes->class([
    'flex flex-col gap-4 md:flex-row',
    $attributes->get('class', 'p-4'),
])->except('wire:model') }}>
    @if ($attributes->has('data-sortable-id'))
        <div class="shrink-0 flex justify-center py-2 cursor-move text-gray-500">
            <x-icon name="sort"/>
        </div>
    @endif

    <div class="grow flex flex-col gap-4">
        @if ($productId = data_get($this, "$model.product_id"))
            <div class="flex items-center justify-between gap-3">
                <x-link target="_blank" icon="open" class="grow"
                    :href="route('app.product.update', [$productId])" 
                    :label="data_get($this, "$model.name")"
                />
            </div>
        @else
            <x-form.text wire:model.lazy="{{ $model }}.name" 
                :placeholder="data_get($columns, 'item_name')"
                :label="false"
            >
                @if (isset(data_get($this, $model)['product_id']) && has_table('products'))
                    <x-slot:button label="Product" icon="cube"
                        wire:click="$emit('openProductModal')"
                    ></x-slot:button>
                @endif
            </x-form.text>
        @endif

        @if (data_get($columns, 'item_description'))
            <x-form.textarea :placeholder="data_get($columns, 'item_description')" :label="false"
                wire:model.lazy="{{ $model }}.description" 
            />
        @endif
    </div>

    <div class="shrink-0 flex flex-col items-end gap-4">
        <div class="flex flex-col md:flex-row gap-4">
            @if (data_get($columns, 'qty'))
                <div class="md:w-40">
                    <x-form.qty :placeholder="data_get($columns, 'qty')" class="text-right" :label="false"
                        wire:model.debounce.500ms="{{ $model }}.qty"
                        min="0"
                    />
                </div>
            @endif

            @if (data_get($columns, 'amount'))
                <div class="md:w-40">
                    <input type="number" class="form-input w-full text-right"
                        wire:model.debounce.500ms="{{ $model }}.amount"
                        :placeholder="data_get($columns, 'amount')"
                        step=".01"
                    >
                </div>
            @endif

            @if (data_get($columns, 'total'))
                <div class="md:w-40 py-2 flex items-center justify-end gap-1">
                    {{ currency(data_get($this, "$model.subtotal")) }}
                </div>
            @endif
        </div>

        @if ($taxes = $attributes->get('taxes') ?? data_get($this, "$model.taxes") ?? [])
            <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
                <div class="flex flex-col gap-2">
                    @foreach ($taxes as $val)
                        @php $addedTax = collect(data_get($this, "$model.taxes"))->firstWhere('id', $val->id) @endphp
                        <div class="flex items-center gap-3">
                            <a
                                wire:click="toggleLineItemTax(@js($id), @js($val->id))"
                                class="grow flex items-center gap-3"
                            >
                                @if ($addedTax) <x-icon name="circle-check" class="text-green-500 shrink-0"/>
                                @else <x-icon name="circle-xmark" class="text-gray-400 shrink-0"/>
                                @endif
                                <div class="grow text-sm">{{ $val->label }}</div>
                            </a>

                            @if ($addedTax)
                                <div class="shrink-0 text-sm">
                                    {{ currency(data_get($addedTax, 'amount')) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <x-link label="Remove" icon="remove" class="text-red-500" size="sm"
            wire:click="removeLineItem('{{ $id }}')"
        />
    </div>
</div>