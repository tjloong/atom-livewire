<div class="flex flex-col gap-6">
    <x-box class="rounded-lg">
        <div class="flex flex-col divide-y">
            <div class="grid md:grid-cols-3">
                <div class="p-5 bg-slate-100">
                    @if ($contact = $document->contact)
                        <div class="flex flex-col gap-6">
                            <x-form.field :label="$this->contactLabel">
                                <div class="bg-white rounded-lg shadow border py-2 px-4 flex gap-2">
                                    <div class="grow flex items-center gap-3">
                                        @if ($logo = $contact->logo) <x-thumbnail :file="$logo" size="20"/> @endif
                        
                                        <div class="font-semibold">
                                            {{ $contact->name }}
                                        </div>
                                    </div>
                        
                                    <a wire:click="$set('document.contact_id', null)" class="shrink-0 flex text-gray-500">
                                        <x-icon name="close" class="m-auto"/>
                                    </a>
                                </div>
                            </x-form.field>

                            <x-form.textarea label="Address"
                                wire:model.defer="document.address"
                            />

                            <x-form.select label="Attention To"
                                wire:model="document.person"
                                :options="$contact->persons->map(fn($val) => $val->name)->unique()"
                                placeholder="Select Person"
                            />
                        </div>
                    @else
                        <x-form.select
                            :label="$this->contactLabel"
                            wire:model="document.contact_id"
                            callback="getContacts"
                            :error="$errors->first('document.contact_id')"
                            required
                        >
                            @if (Route::has('app.contact.create'))
                                <x-slot:footlink 
                                    label="Create New"
                                    :href="route('app.contact.create', [$this->contactType])"
                                ></x-slot:footlink>
                            @endif
                        </x-form.select>        
                    @endif
                </div>

                <div class="md:col-span-2">
                    <div class="p-5 pb-8 grid gap-6 md:grid-cols-2">
                        <x-form.text label="Number"
                            wire:model.defer="document.postfix"
                            :prefix="$document->prefix"
                            :error="$errors->first('document.postfix')"
                            required
                        />
                
                        <x-form.date label="Issue Date"
                            wire:model="document.issued_at"
                        />

                        <x-form.text label="Reference"
                            wire:model.defer="document.reference"
                        />

                        @isset($document->data->valid_for)
                            <x-form.number label="Valid For"
                                wire:model.defer="document.data.valid_for"
                                postfix="day(s)"
                            />
                        @endisset

                        @if (in_array($document->type, ['invoice', 'bill', 'delivery-order']))
                            <x-form.select
                                :label="[
                                    'invoice' => 'Quotation',
                                    'bill' => 'Purchase Order',
                                    'delivery-order' => 'Invoice',
                                ][$document->type]"
                                wire:model="document.converted_from_id"
                                callback="getConvertFromDocuments"
                            />
                        @endif

                        @if ($document->type === 'delivery-order')
                            <x-form.date label="Delivered Date"
                                wire:model="document.delivered_at"
                            />

                            <x-form.select label="Delivery Channel"
                                wire:model="document.data.delivery_channel"
                                :options="account_settings('delivery_order.channels', [])"
                            />
                        @elseif ($document->type === 'purchase-order')
                            <x-form.textarea label="Deliver To"
                                wire:model.defer="document.data.deliver_to"
                            />
                        @else
                            <x-form.text label="Payment Term"
                                wire:model.defer="document.payterm"
                            />

                            <x-form.text label="Description"
                                wire:model.defer="document.description"
                            />
                        @endif
                    </div>
                </div>
            </div>

            @livewire(lw('app.document.form.item'), [
                'document' => $document,
            ], key('items'))

            @if ($this->totals)
                <div class="p-4 grid md:grid-cols-12">
                    <div class="flex flex-col gap-3 md:col-span-8 md:col-start-5">
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
                                        <div class="flex items-center justify-end gap-4">
                                            <div wire:click="openCurrencyModal" class="text-lg flex items-center gap-2 cursor-pointer">
                                                {{ $document->currency }} <x-icon name="chevron-down" size="10"/>
                                            </div>
                                            <div class="text-lg font-semibold">
                                                {{ currency(data_get($total, 'amount')) }}
                                            </div>
                                        </div>
                                    @else
                                        {{ currency(data_get($total, 'amount'), $document->currency) }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (count($this->currencies) > 1)
                        @livewire(lw('app.document.form.currency-modal'), key('currency-modal'))
                    @endif
                </div>
            @endif

            @if ($document->type !== 'bill')
                <div class="p-4 grid gap-6">
                    <x-form.textarea label="Note"
                        wire:model.defer="document.note"
                    />

                    <x-form.text label="Footer" 
                        wire:model.defer="document.footer"
                    />
                </div>
            @endif

            <div class="p-4 grid gap-6 md:grid-cols-2">
                <x-form.select.owner
                    wire:model="document.owned_by"
                />
            </div>

            @if ($errors->any())
                <div class="p-4">
                    <x-alert :errors="$errors->all()"/>
                </div>
            @endif
        </div>

        <x-slot:foot>
            <x-button.submit type="button"
                wire:click="submit"
            />
        </x-slot:foot>
    </x-box>
</div>