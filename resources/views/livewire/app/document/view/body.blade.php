<x-box class="rounded-lg">
    <div class="flex flex-col divide-y">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-2/5 bg-slate-100">
                <x-form.group>
                    <x-form.field label="Contact">
                        @if (Route::has('contact.view') && user()->can('contact.view')) <x-link :label="$document->name" :href="route('app.contact.view', [$document->contact_id])"/>
                        @else <div class="font-medium">{{ $document->name }}</div>
                        @endif
                        <div class="text-sm text-gray-500 font-medium">{{ $document->address }}</div>
                    </x-form.field>
                    @if ($person = $document->person) <x-form.field label="Attention To" :value="$person"/> @endif
                </x-form.group>
            </div>

            <div class="md:w-3/5">
                <div class="flex flex-col divide-y">
                    @foreach ($this->infoFields as $key => $val)
                        <x-field :label="$key"
                            :value="is_string($val) ? $val : data_get($val, 'value')"
                            :href="data_get($val, 'href')"
                            :badge="data_get($val, 'badge')"
                            :tags="data_get($val, 'tags')"
                        />
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col divide-y border-y">
            <div class="hidden py-3 px-4 md:flex md:gap-4">
                @foreach ([
                    'item_name' => 'grow',
                    'qty' => 'md:w-48 text-right',
                    'price' => 'md:w-48 text-right',
                    'total' => 'md:w-48 text-right',
                ] as $key => $class)
                    @if ($col = $this->columns->get($key))
                        <div class="{{ $class }} text-sm font-medium text-gray-500">{{ __(str()->upper($col)) }}</div>
                    @endif
                @endforeach
            </div>
        
            <div class="flex flex-col divide-y">
                @forelse ($this->items as $item)
                    <div class="py-3 px-4 flex flex-col md:flex-row gap-4">
                        <div class="grow flex flex-col gap-2">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="grow grid gap-2">
                                    <div class="font-medium">{{ $item->name }}</div>
                                    @if ($this->columns->get('item_description') && $item->description)
                                        <div class="text-sm text-gray-500 md:hidden">{!! nl2br($item->description) !!}</div>
                                    @endif    
                                </div>
        
                                @if ($this->columns->get('qty'))
                                    <div class="shrink-0 md:w-48 md:text-right">
                                        <div class="text-xs font-medium text-gray-400 md:hidden">{{ str()->upper($this->columns->get('qty')) }}</div>
                                        {{ $item->qty }}
                                    </div>
                                @endif
        
                                @if ($this->columns->get('price'))
                                    <div class="shrink-0 md:w-48 md:text-right">
                                        <div class="text-xs font-medium text-gray-400 md:hidden">{{ str()->upper($this->columns->get('price')) }}</div>
                                        {{ currency($item->amount) }}
                                    </div>
                                @endif
                            </div>
        
                            @if ($this->columns->get('item_description') && $item->description)
                                <div class="hidden text-sm text-gray-500 md:block">{!! nl2br($item->description) !!}</div>
                            @endif
                        </div>
        
                        @if ($this->columns->get('total'))
                            <div class="shrink-0 md:w-48 md:text-right">
                                <div class="grid gap-4">
                                    <div>
                                        <div class="text-xs font-medium text-gray-400 md:hidden">{{ str()->upper($this->columns->get('total')) }}</div>
                                        {{ currency($item->subtotal, null, false) }}
                                    </div>
        
                                    @if (enabled_module('taxes') && $this->columns->has('tax'))
                                        @if ($taxes = $item->taxes)
                                            <div class="flex flex-col">
                                                @foreach ($taxes as $tax)
                                                    <div class="flex items-center gap-4 text-sm md:justify-end">
                                                        <div class="text-gray-500">{{ $tax->label }}</div>
                                                        <div>{{ currency($tax->pivot->amount) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <x-empty-state title="No items" subtitle="This document has no items" size="sm"/>
                @endforelse
            </div>
        </div>

        @if ($document->type !== 'delivery-order')
            <div class="p-4">
                <div class="bg-slate-100 rounded-lg p-2 flex flex-col gap-2 md:w-1/2 md:ml-auto">
                    <div class="px-2 flex items-center justify-between gap-2">
                        <div class="font-medium">{{ __('Subtotal') }}</div>
                        <div class="font-medium">{{ currency($document->subtotal, $document->currency) }}</div>
                    </div>
            
                    @foreach ($document->getTaxes() as $tax)
                        <div class="px-2 flex items-center justify-between gap-2 text-sm">
                            <div>{{ data_get($tax, 'label') }}</div>
                            <div class="font-medium">{{ currency(data_get($tax, 'amount')) }}</div>
                        </div>
                    @endforeach
            
                    <div class="bg-slate-200 rounded-md p-2">
                        <div class="flex items-center justify-between gap-2">
                            <div class="font-bold">{{ __('Grand Total') }}</div>
                            <div class="font-bold">{{ currency($document->grand_total, $document->currency) }}</div>
                        </div>
            
                        @auth
                            @if ($document->is_foreign_currency)
                                <div class="text-sm text-right font-medium text-gray-500">
                                    {{ currency($document->calculateCurrencyConversion('grand_total'), $document->master_currency) }}
                                </div>
                            @endif
                        @endauth
                    </div>
            
                    @if ($document->splitted_total)
                        <div class="bg-slate-200 rounded-md p-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-bold">{{ __('Amount to be Paid') }}</div>
                                <div class="font-bold">{{ currency($document->splitted_total, $document->currency) }}</div>
                            </div>
            
                            @auth
                                @if ($document->is_foreign_currency)
                                    <div class="text-sm text-right font-medium text-gray-500">
                                        {{ currency($document->calculateCurrencyConversion('splitted_total'), $document->master_currency) }}
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        @endif
    
        <div class="p-4 grid gap-4">
            @if ($note = $document->note) <x-form.field label="Note" :value="$note" class="text-sm"/> @endif
            @if ($footer = $document->footer) <x-form.field label="Footer" :value="$footer" class="text-sm"/> @endif
            @if (!$note && !$footer) <div class="text-gray-400 text-center">{{ __('No footer') }}</div> @endif
        </div>
    </div>
</x-box>
