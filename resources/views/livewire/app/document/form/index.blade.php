<div class="flex flex-col gap-6">
    <x-form>
        <div class="grid md:grid-cols-3">
            @livewire(lw('app.document.form.contact'), compact('document'), key('contact'))

            <div class="md:col-span-2">
                @livewire(lw('app.document.form.info'), compact('document', 'settings'), key('info'))
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
                <x-form.sortable
                    wire:sorted="sortItems"
                    :config="['handle' => '.cursor-move']"
                    class="flex flex-col divide-y"
                >
                    @foreach ($items as $i => $item)
                        @php $id = data_get($item, 'id') ?? data_get($item, 'ulid') @endphp
                        <div class="flex py-4" data-sortable-id="{{ $id }}">
                            <div class="flex w-8 h-8 {{ count($items) > 1 ? 'cursor-move' : 'opacity-50' }}">
                                <x-icon name="sort" class="m-auto text-gray-500"/>
                            </div>
    
                            <div class="grow pl-2 pr-4">
                                @livewire(lw('app.document.form.item'), [
                                    'item' => $item,
                                    'columns' => $columns,
                                    'document' => $document,
                                ], key($id))
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
            @livewire(lw('app.document.form.total'), compact('document', 'items'), key('total'))
        @endif

        @if ($document->type !== 'bill')
            @livewire(lw('app.document.form.footer'), compact('document', 'settings'), key('footer'))
        @endif

        @livewire(lw('app.document.form.additional-info'), compact('document'), key('additional-info'))
    </x-form>

    @module('products')
        @livewire(lw('app.document.form.product-modal'), [
            'currency' => $document->currency,
        ], key(uniqid()))
    @endmodule
</div>