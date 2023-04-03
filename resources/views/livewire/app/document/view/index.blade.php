<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title" back>
        <div class="flex items-center gap-2">
            @can($document->type.'.update')
                @if ($master = $document->splittedFrom)
                    <x-button.confirm color="gray" label="Edit"
                        title="Edit Splitted Invoice"
                        :message="'This invoice is splitted from #'.$master->number.'. Do you want to edit the master invoice?'"
                        :href="route('app.document.update', [$document->id])"
                    />
                @else
                    <x-button color="gray" label="Edit" :href="route('app.document.update', [$document->id])"/>
                @endif
            @endcan
        
            <x-dropdown>
                <x-slot:anchor>
                    <x-button color="gray" label="More" icon="postfix:chevron-down"/>
                </x-slot:anchor>
        
                <x-dropdown.item wire:click="pdf" label="View PDF" icon="pdf" target="_blank"/>
                
                @can($document->type.'.update')
                    <x-dropdown.item wire:click="$emitTo('{{ lw('app.document.view.email-modal') }}', 'open')" label="Send Email" icon="paper-plane"/>
                    <x-dropdown.item wire:click="toggleSent" :label="$document->last_sent_at ? 'Unmark Sent' : 'Mark Sent'" icon="share-from-square"/>
                    <x-dropdown.item x-on:click="$dispatch('shareable-open')" label="Share" icon="share-nodes" class="cursor-pointer"/>
                @endcan
                
                @can($document->type.'.delete')
                    <x-dropdown.delete
                        :title="str()->headline('Delete '.$document->type)"
                        message="Are you sure to delete this document?"
                    />
                @endcan
            </x-dropdown>
        </div>
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-8/12">
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

                    <div class="flex flex-col divide-y">
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
        </div>

        <div class="md:w-4/12">
            <div class="flex flex-col gap-6">
                <x-box header="Information">
                    <div class="flex flex-col divide-y">
                        @foreach ($this->additionalInfoFields as $key => $val)
                            <x-field :label="$key"
                                :value="is_string($val) ? $val : data_get($val, 'value')"
                                :href="data_get($val, 'href')"
                                :badge="data_get($val, 'badge')"
                                :tags="data_get($val, 'tags')"
                            />
                        @endforeach
                    </div>
                </x-box>
                
                @if ($document->convertedTo()->count())
                    @livewire(lw('app.document.view.converted'), compact('document'), key('converted'))
                @endif

                @if (
                    $document->type === 'invoice'
                    && ($document->splits()->count() || !in_array($document->status, ['paid', 'partial']))
                )
                    @livewire(lw('app.document.view.split'), compact('document'), key('split'))
                @endif

                @if (in_array($document->type, ['invoice', 'bill']))
                    @livewire(lw('app.document.view.payment'), compact('document'), key('payment'))
                @endif

                @livewire(lw('app.document.view.attachment'), compact('document'), key('attachment'))
            </div>
        </div>
    </div>
    
    <x-shareable :shareable="$document->shareable"/>

    @livewire(lw('app.document.view.email-modal'), compact('document'), key('email-modal'))
</div>