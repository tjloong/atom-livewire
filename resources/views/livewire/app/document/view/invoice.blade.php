<x-box header="Invoices">
    <div class="grid divide-y">
        <div class="max-h-[150px] overflow-auto">
            @foreach ($this->invoices as $invoice)
                <div class="py-2 px-4 text-sm hover:bg-slate-100">
                    <div class="flex gap-2 flex-wrap">
                        <div class="grow">
                            <a href="{{ route('app.document.view', [$invoice->id]) }}">
                                {{ $invoice->number }}
                            </a>
                        </div>

                        <div class="shrink-0 text-gray-500 font-medium">
                            {{ currency($invoice->splitted_total ?? $invoice->grand_total, $invoice->currency) }}
                        </div>
                    </div>

                    <div class="flex gap-2 flex-wrap">
                        <div class="grow">
                            {{ format_date($invoice->issued_at) }}
                        </div>
                        
                        <div class="shrink-0">
                            <x-badge :label="$invoice->status"/>
                        </div>
                    </div>
                </div>            
            @endforeach
        </div>

        <div class="p-4">
            <x-button color="gray" icon="plus" block
                label="Create Invoice"
                :href="route('app.document.create', [
                    'type' => 'invoice',
                    'convertFrom' => $document->id,
                ])"
            />
        </div>
    </div>
</x-box>
