<x-box header="Bills">
    <div class="grid divide-y">
        <div class="max-h-[150px] overflow-auto">
            @foreach ($this->bills as $bill)
                <div class="py-2 px-4 text-sm hover:bg-slate-100">
                    <div class="flex gap-2 flex-wrap">
                        <div class="grow">
                            <a href="{{ route('app.document.view', [$bill->id]) }}">
                                {{ $bill->number }}
                            </a>
                        </div>

                        <div class="shrink-0 text-gray-500 font-medium">
                            {{ currency($bill->grand_total, $bill->currency) }}
                        </div>
                    </div>

                    <div class="flex gap-2 flex-wrap">
                        <div class="grow">
                            {{ format_date($bill->issued_at) }}
                        </div>
                        
                        <div class="shrink-0">
                            <x-badge :label="$bill->status"/>
                        </div>
                    </div>
                </div>            
            @endforeach
        </div>

        <div class="p-4">
            <x-button color="gray" icon="plus" block
                label="Create Bill"
                :href="route('app.document.create', [
                    'type' => 'bill',
                    'convertFrom' => $document->id,
                ])"
            />
        </div>
    </div>
</x-box>
