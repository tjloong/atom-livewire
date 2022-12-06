<div class="flex flex-col divide-y">
    <div class="hidden py-3 px-4 md:flex md:gap-4">
        @foreach ([
            'item_name' => 'grow',
            'qty' => 'md:w-40 text-right',
            'price' => 'md:w-40 text-right',
            'total' => 'md:w-40 text-right',
        ] as $key => $class)
            @if ($col = $this->columns->get($key))
                <div class="{{ $class }} text-sm font-medium text-gray-500">
                    {{ __(str()->upper($col)) }}
                </div>
            @endif
        @endforeach

        <div class="md:w-10"></div>
    </div>

    @if ($items)
        <x-form.sortable
            wire:sorted="sortItems"
            :config="['handle' => '.cursor-move']"
            class="flex flex-col divide-y"
        >
            @foreach ($items as $i => $item)
                <div class="py-3 px-4 flex flex-col gap-4" data-sortable-id="{{ $i }}">
                    <div class="flex flex-col gap-4 md:flex-row">
                        <div class="grow flex">
                            <div class="cursor-move text-gray-400 -ml-4 px-4 mt-2">
                                <x-icon name="sort"/>
                            </div>

                            <div class="grow flex flex-col gap-4">
                                @if (data_get($item, 'product_id'))
                                    <div class="py-2">
                                        {{ data_get($item, 'name') }}
                                    </div>
                                @else
                                    <x-form.text :placeholder="$this->columns->get('item_name')"
                                        wire:model.debounce.300ms="items.{{ $i }}.name"
                                    />
                                @endif

                                @if ($this->columns->get('item_description'))
                                    <x-form.textarea :placeholder="$this->columns->get('item_description')"
                                        wire:model.debounce.300ms="items.{{ $i }}.description"
                                    />
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 flex flex-col gap-4">
                            <div class="flex flex-col md:flex-row gap-4">
                                @if ($this->columns->get('qty'))
                                    <div class="md:w-40">
                                        <x-form.number :placeholder="$this->columns->get('qty')"
                                            wire:model.debounce.300ms="items.{{ $i }}.qty"
                                            class="text-right"
                                        />
                                    </div>
                                @endif
    
                                @if ($this->columns->get('price'))
                                    <div class="md:w-40">
                                        <x-form.amount :placeholder="$this->columns->get('price')"
                                            wire:model.debounce.300ms="items.{{ $i }}.amount"
                                            class="text-right"
                                        />
                                    </div>
                                @endif
    
                                @if ($this->columns->get('total'))
                                    <div class="md:w-40 py-2 flex items-center justify-end gap-1">
                                        <div class="text-sm text-gray-400">{{ $document->currency }}</div>
                                        <div>{{ number_format(data_get($item, 'subtotal'), 2) }}</div>
                                    </div>
                                @endif
                            </div>

                            @if ($this->columns->get('price'))
                                @if ($recommended = data_get($item, 'metadata.recommended_price'))
                                    @if (
                                        (float)data_get($recommended, 'amount') > 0
                                        && (float)data_get($item, 'amount') !== (float)data_get($recommended, 'amount')
                                    )
                                        <div class="bg-yellow-100 text-sm py-2 px-4 rounded-lg border border-yellow-200 flex items-center gap-2">
                                            <x-icon name="circle-info" size="12" class="shrink-0 text-yellow-500"/>
                                            <div class="grow text-yellow-800 font-medium">
                                                {{ __('Recommended '.strtolower($this->columns->get('price'))) }}:
                                                {{ currency(data_get($recommended, 'amount'), $document->currency) }}
                                            </div>
                                            <a wire:click="$set('items.{{ $i }}.amount', @js(data_get($recommended, 'amount')))" class="shrink-0">
                                                {{ __('Use') }}
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endif

                            @if ($this->columns->has('tax'))
                                <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
                                    @foreach (data_get($item, 'taxes') as $tax)
                                        <div class="py-2 px-4 flex items-center gap-2">
                                            <a wire:click="removeTax(
                                                @js($i),
                                                @js(data_get($tax, 'id'))
                                            )" class="text-red-500 flex">
                                                <x-icon name="remove" class="m-auto"/>
                                            </a>
                                            <div class="grow">{{ data_get($tax, 'label') }}</div>
                                            <div class="shrink-0">{{ currency(data_get($tax, 'amount')) }}</div>
                                        </div>
                                    @endforeach

                                    @if (count($this->taxes))
                                        <div class="p-3">
                                            <x-form.select 
                                                :label="data_get($item, 'taxes') ? null : $this->columns->get('tax')"
                                                :placeholder="'Select '.$this->columns->get('tax')"
                                                :options="$this->taxes"
                                                x-on:input="$wire.call('addTax', {{ $i }}, $event.detail).then(() => value = null)"
                                            />
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="shrink-0 md:w-10 md:flex md:justify-center">
                            <a
                                wire:click="removeItem(@js($i))"
                                class="text-red-500 mt-2 flex items-center gap-2 md:block"
                            >
                                <x-icon name="remove"/>
                                <div class="md:hidden">{{ __('Remove') }}</div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </x-form.sortable>
    @endif

    <a wire:click="openProductModal" class="p-4 flex items-center justify-center gap-2 hover:bg-slate-100">
        <x-icon name="add"/> {{ __('Add Item') }}
    </a>

    @livewire(lw('app.document.form.product-modal'))
</div>
