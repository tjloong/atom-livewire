<x-box header="Splitted Invoices">
    <div class="grid divide-y">
        @if ($this->splits->count())
            <div class="max-h-[150px] overflow-auto">
                <div class="flex flex-col divide-y">
                    @foreach ($this->splits as $split)
                        <div class="py-2 px-4 text-sm hover:bg-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="grow">
                                    @if ($split->id === $document->id)
                                        <div class="font-medium">
                                            {{ $split->number }} <span class="text-gray-400">({{ __('current') }})</span>
                                        </div>
                                    @else
                                        <a href="{{ route('app.document.view', [$split->id]) }}" class="font-medium">
                                            {{ $split->number }}
                                        </a>
                                    @endif
                                </div>
    
                                <div class="shrink-0">
                                    <div class="text-gray-500 font-medium">
                                        {{ currency($split->splitted_total, $split->currency) }}
                                    </div>
                                </div>
                            </div>
    
                            <div class="flex items-center gap-2">
                                <div class="grow">
                                    {{ format_date($split->issued_at) }}
                                </div>
    
                                <div class="shrink-0">
                                    <x-badge :label="$split->status" size="xs"/>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (!in_array($document->status, ['paid', 'partial']))
            <div class="p-4">
                <x-button color="gray" block
                    :icon="$this->splits->count() ? 'edit' : 'scissors'"
                    :label="$this->splits->count() ? 'Edit Splits' : 'Split Invoice'"
                    :href="route('app.document.split', [$document->id])"
                />
            </div>
        @endif
    </div>
</x-box>
