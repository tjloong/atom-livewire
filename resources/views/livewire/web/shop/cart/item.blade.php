<div class="relative">
    <div class="absolute inset-0 bg-white/50 hidden" wire:loading.class.remove="hidden"></div>

    @if (!$small && !$readonly)
        <div class="hidden p-4 bg-gray-100 font-semibold md:grid md:grid-cols-12 md:gap-4">
            <div class="md:col-span-6">{{ __('Item') }}</div>
            <div class="md:col-span-2 text-right">{{ __('Price') }}</div>
            <div class="md:col-span-2 text-right">{{ __('Quantity') }}</div>
            <div class="md:col-span-2 text-right">{{ __('Total') }}</div>
        </div>
    @endif

    <div class="flex flex-col divide-y">
        @forelse ($items ?? [] as $i => $item)
            <div class="p-4 hover:bg-slate-100">
                @if ($readonly)
                    <div class="flex gap-4">
                        <div class="shrink-0 flex items-center justify-center">
                            <x-thumbnail size="70" :url="optional($item->image)->url"/>
                        </div>
        
                        <div class="grow flex flex-col gap-2">
                            <div class="flex flex-col">
                                <x-link text :label="$item->name" :href="route('web.shop.product', [$item->product->slug])"/>
        
                                @if ($item->variant_name) 
                                    <div class="font-medium text-gray-500 truncate">
                                        {{ $item->variant_name }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                <x-badge :label="$item->qty.' x '.currency($item->amount)"/>
                            </div>
                        </div>

                        <div class="shrink-0">
                            {{ currency($item->subtotal) }}
                        </div>
                    </div>
                @elseif ($small)
                    <div class="flex gap-4">
                        <div class="shrink-0 flex items-center justify-center">
                            <x-thumbnail size="70" :url="optional($item->image)->url"/>
                        </div>
        
                        <div class="grow flex flex-col gap-2">
                            <div class="flex flex-col">
                                <x-link text :label="$item->name" :href="route('web.shop.product', [$item->product->slug])"/>
        
                                @if ($item->variant_name) 
                                    <div class="font-medium text-gray-500 truncate">
                                        {{ $item->variant_name }}
                                    </div>
                                @endif
        
                                <div class="font-medium text-gray-500 truncate">
                                    {{ currency($item->amount) }}
                                </div>
                            </div>
                            
                            <div class="md:w-48">
                                <x-form.qty wire:model.debounce.500ms="items.{{ $i }}.qty" min="1" :label="false" uuid/>
                            </div>
                        </div>
        
                        <div class="shrink-0 flex items-center gap-3">
                            {{ currency($item->subtotal) }}
        
                            <x-close.delete icon="trash-can"
                                title="Remove From Cart"
                                message="Are you sure to remove this product from cart?"
                                callback="remove"
                                :params="$item->id"
                            />
                        </div>
                    </div>
                @else
                    <div class="grid items-center gap-4 md:grid-cols-12">
                        <div class="md:col-span-6 flex flex-col gap-4 md:flex-row md:items-center">
                            <x-thumbnail size="70" :url="optional($item->image)->url"/>

                            <div class="flex flex-col gap-2">
                                <div>
                                    <x-link text :label="$item->name" :href="route('web.shop.product', [$item->product->slug])"/>
            
                                    @if ($item->variant_name) 
                                        <div class="font-medium text-gray-500 truncate">
                                            {{ $item->variant_name }}
                                        </div>
                                    @endif
                                </div>

                                <div class="w-32">
                                    <x-button.delete size="xs" outlined
                                        title="Remove From Cart"
                                        message="Are you sure to remove this product from cart?"
                                        callback="remove"
                                        :params="$item->id"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 md:text-right">
                            {{ currency($item->amount) }}
                        </div>

                        <div class="md:col-span-2">
                            <div class="w-48">
                                <x-form.qty wire:model.debounce.500ms="items.{{ $i }}.qty" min="1" :label="false" uuid/>
                            </div>
                        </div>

                        <div class="md:col-span-2 md:text-right">
                            {{ currency($item->grand_total) }}
                        </div>
                    </div>
                @endif                
            </div>
        @empty
            <x-empty-state title="No Items" subtitle="Your shopping cart is empty"/>
        @endforelse
    </div>
</div>
