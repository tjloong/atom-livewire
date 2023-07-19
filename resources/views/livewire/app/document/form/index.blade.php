<div class="flex flex-col gap-6">
    <x-form>
        <div class="grid md:grid-cols-3">
            <div class="bg-slate-100">
                <x-form.group>
                    @if ($document->convertedFrom)
                        <x-form.field :label="$this->contactLabel" :value="$document->name"/>
                        <x-form.field label="Address" :value="$document->address"/>
                        @if ($document->person) <x-form.field label="Attention To" :value="$document->person"/> @endif
                    @else
                        <x-form.select wire:model="document.contact_id" 
                            wire:search="$set('filters.contact', $event.detail)" 
                            :label="$this->contactLabel" 
                            :options="data_get($this->options, 'contacts')"
                        >
                            @if (has_route('app.contact.create'))
                                <x-slot:footlink label="Create New" :href="route('app.contact.create', [$document->type])"></x-slot:footlink>
                            @endif
                        </x-form.select>

                        @if ($document->contact)
                            @if ($addresses = data_get($this->options, 'addresses'))
                                <x-form.select wire:model.defer="document.address" :options="$addresses" uuid autocomplete/>
                            @else
                                <x-form.text wire:model.defer="document.address"/>
                            @endif

                            @if ($persons = $document->contact->persons->pluck('name')->unique()->toArray())
                                <x-form.select wire:model.defer="document.person" label="Attention To" :options="$persons" autocomplete/>
                            @else
                                <x-form.text wire:model.defer="document.person"/>
                            @endif
                        @endif
                    @endif
                </x-form.group>
            </div>

            <div class="md:col-span-2">
                <x-form.group cols="2">
                    <x-form.text wire:model.defer="document.postfix" label="Number" :prefix="$document->prefix" placeholder="Leave empty to auto generate"/>
                    <x-form.date wire:model="document.issued_at" label="Issue Date"/>
                    <x-form.text wire:model.defer="document.reference"/>
                
                    @isset($document->data->valid_for)
                        <x-form.number wire:model.defer="document.data.valid_for" postfix="day(s)"/>
                    @endisset
                
                    @if (
                        in_array($document->type, ['invoice', 'bill', 'delivery-order'])
                        && ($sources = data_get($this->options, 'sources'))
                    )
                        <x-form.select wire:model="document.converted_from_id" :label="$this->sourceLabel" 
                            wire:search="$set('filters.source', $event.detail)"
                            :options="$sources"
                        />
                    @endif
                
                    @if ($document->type === 'delivery-order')
                        <x-form.date wire:model="document.delivered_at" label="Delivered Date"/>
                        <x-form.select wire:model="document.data.delivery_channel" :options="data_get($this->options, 'delivery_channels')"/>
                    @elseif ($document->type === 'purchase-order')
                        <x-form.textarea wire:model.defer="document.data.deliver_to"/>
                    @else
                        <x-form.text wire:model.defer="document.payterm" label="Payment Term"/>
                        <x-form.text wire:model.defer="document.description"/>
                    @endif
                </x-form.group>
            </div>
        </div>

        <div class="flex flex-col divide-y border-t">
            <div class="hidden py-3 p-4 md:flex md:gap-4">
                @foreach ([
                    'item_name' => 'grow',
                    'qty' => 'md:w-40 text-right',
                    'price' => 'md:w-40 text-right',
                    'total' => 'md:w-40 text-right',
                ] as $key => $class)
                    @if ($col = $columns->get($key))
                        <div class="{{ $class }} text-sm font-medium text-gray-500">
                            {{ __(str()->upper($col)) }}
                        </div>
                    @endif
                @endforeach
        
                <div class="md:w-10"></div>
            </div>

            @if ($items)
                <x-form.sortable wire:sorted="sortItems" :config="['handle' => '.cursor-move']" class="flex flex-col divide-y">
                    @foreach ($items as $i => $item)
                        @php $id = data_get($item, 'id') ?? data_get($item, 'ulid') @endphp
                        <div class="flex py-4" data-sortable-id="{{ $id }}">
                            <div class="flex w-8 h-8 {{ count($items) > 1 ? 'cursor-move' : 'opacity-50' }}">
                                <x-icon name="sort" class="m-auto text-gray-500"/>
                            </div>
    
                            <div class="grow pl-2 pr-4">
                                @livewire(atom_lw('app.document.form.item'), [
                                    'item' => $item,
                                    'columns' => $columns,
                                    'document' => $document,
                                ], key($id.'-'.$fingerprint))
                            </div>
                        </div>
                    @endforeach
                </x-form.sortable>
            @endif

            <a wire:click="addItem" class="p-4 flex items-center justify-center gap-2 hover:bg-slate-100">
                <x-icon name="add"/> {{ __('Add Item') }}
            </a>
        </div>

        @if ($items)
            <x-form.group>
                @livewire(atom_lw('app.document.form.total'), compact('document', 'items'), key('total-'.$fingerprint))
            </x-form.group>
        @endif

        @if ($document->type !== 'bill')
            <x-form.group>
                <x-form.textarea wire:model.defer="document.note"/>
                <x-form.text wire:model.defer="document.footer"/>
            </x-form.group>
        @endif

        <x-form.group cols="2">
            <x-form.select.owner wire:model="document.owned_by"/>
            <x-form.select.label wire:model="inputs.labels" type="document" multiple/>
        </x-form.group>
    </x-form>

    @if (has_table('products'))
        @livewire(atom_lw('app.document.form.product-modal'), [
            'currency' => $document->currency,
        ], key('product-modal-'.$fingerprint))
    @endif 
</div>