<x-box header="Related Documents">
    <div class="max-h-[150px] overflow-auto">
        @foreach ($this->document->convertedTo()->latest('issued_at')->latest('id') as $converted)
            <div class="py-2 px-4 text-sm hover:bg-slate-100">
                <div class="flex gap-2 flex-wrap">
                    <div class="grow">
                        <a href="{{ route('app.document.view', [$converted->id]) }}">
                            {{ $converted->number }}
                        </a>
                    </div>

                    <div class="shrink-0 text-gray-500 font-medium">
                        {{ 
                            $converted->type === 'invoice'
                                ? currency($converted->splitted_total ?? $converted->grand_total, $converted->currency)
                                : currency($converted->grand_total, $converted->currency)
                        }}
                    </div>
                </div>

                <div class="flex gap-2 flex-wrap">
                    <div class="grow">
                        {{ format_date($converted->issued_at) }}
                    </div>
                    
                    <div class="shrink-0">
                        <x-badge :label="$converted->status"/>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-box>