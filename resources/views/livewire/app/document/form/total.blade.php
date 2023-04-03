<div class="md:w-3/5 md:ml-auto">
    @foreach ($this->totals as $total)
        @php $isGrandTotal = data_get($total, 'label') === 'Grand Total' @endphp

        <div class="flex flex-row gap-2 {{
            $isGrandTotal ? 'bg-slate-100 py-2 px-4 md:rounded-lg' : 'px-4'
        }}">
            <div class="md:w-2/3 md:text-right font-bold text lg">
                {{ __(data_get($total, 'label')) }}
            </div>
            <div class="md:w-1/3 text-lg text-right">                    
                @if ($isGrandTotal && count($this->currencies) > 1)
                    <div class="inline-flex items-center gap-3">
                        <x-dropdown :label="data_get($inputs, 'currency')">
                            <div class="flex flex-col divide-y">
                                @foreach ($this->currencies as $opt)
                                    <div 
                                        wire:click="$set('inputs.currency', @js(data_get($opt, 'name')))"
                                        class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-100"
                                    >
                                        <x-icon 
                                            name="circle-check" 
                                            :class="data_get($opt, 'name') === data_get($inputs, 'currency') 
                                                ? 'text-green-500' 
                                                : 'text-gray-300'"
                                        />

                                        {{ data_get($opt, 'name') }}
                                    </div>
                                @endforeach

                                @if ($this->isForeignCurrency)
                                    <div class="p-4">
                                        <x-form.number label="Rate"
                                            wire:model.lazy="inputs.currency_rate"
                                            step=".01"
                                            placeholder="Conversion Rate"
                                        />
                                    </div>
                                @endif
                            </div>
                        </x-dropdown>
                        
                        {{ currency(data_get($total, 'amount')) }}
                    </div>

                    @if ($this->isForeignCurrency)
                        <div class="text-sm text-gray-500 font-medium">
                            {{ __('Rate') }}: {{ data_get($inputs, 'currency_rate') }}
                        </div>
                    @endif
                @else
                    {{ currency(data_get($total, 'amount'), $document->currency) }}
                @endif
            </div>
        </div>
    @endforeach
</div>
