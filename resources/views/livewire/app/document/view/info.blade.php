<x-box class="rounded-lg">
    <div class="flex flex-col divide-y">
        <div class="grid md:grid-cols-12">
            <div class="md:col-span-5 bg-slate-100 p-4 grid gap-4">
                <x-form.field label="Contact">
                    <div class="grid">
                        @if (Route::has('contact.view') && auth()->user()->can('contact.view'))
                            <a href="{{ route('app.contact.view', [$document->contact_id]) }}">
                                {{ $document->name }}
                            </a>
                        @else
                            <div class="font-semibold">
                                {{ $document->name }}
                            </div>
                        @endif
                        
                        <div class="text-sm text-gray-500 font-medium">
                            {{ $document->address }}
                        </div>
                    </div>
                </x-form.field>

                @if ($person = $document->person)
                    <x-form.field label="Attention To">
                        {{ $person }}
                    </x-form.field>
                @endif
            </div>

            <div class="md:col-span-7 p-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-form.field :label="str()->headline($document->type.' #')">
                        {{ $document->number }}
                    </x-form.field>

                    <x-form.field label="Issued Date">
                        {{ format_date($document->issued_at) }}
                    </x-form.field>

                    <x-form.field label="Status">
                        <x-badge :label="$document->status" size="md"/>
                    </x-form.field>

                    @if ($validfor = data_get($document->data, 'valid_for'))
                        <x-form.field label="Valid For">
                            {{ $validfor }} {{ __('day(s)') }}
                        </x-form.field>
                    @endif

                    @if ($ref = $document->reference)
                        <x-form.field label="Reference">
                            {{ $ref }}
                        </x-form.field>
                    @endif

                    @if ($convertedFrom = $document->convertedFrom)
                        <x-form.field
                            :label="[
                                'invoice' => 'Quotation',
                                'bill' => 'Purchase Order',
                                'delivery-order' => 'Invoice',
                            ][$document->type]"
                        >
                            <a href="{{ route('app.document.view', [$convertedFrom->id]) }}">
                                {{ $convertedFrom->number }}
                            </a>
                        </x-form.field>
                    @endif

                    @if ($document->type === 'delivery-order')
                        <x-form.field label="Delivery Channel">
                            {{ data_get($document->data, 'delivery_channel') }}
                        </x-form.field>

                        @if ($d = $document->delivered_at)
                            <x-form.field label="Delivered Date">
                                {{ format_date($d) }}
                            </x-form.field>
                        @endif
                    @elseif ($to = data_get($document->data, 'deliver_to'))
                        <x-form.field label="Deliver To" class="text-sm">
                            {!! nl2br($to) !!}
                        </x-form.field>
                    @else
                        @if ($payterm = $document->formatted_payterm)
                            <x-form.field label="Payment Terms">
                                {{ $payterm }}
                            </x-form.field>
                        @endif

                        @if ($desc = $document->description)
                            <x-form.field label="Description">
                                {{ $desc }}
                            </x-form.field>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        @livewire(lw('app.document.view.item'), [
            'document' => $document,
            'columns' => $document->getColumns(),
        ], key('items'))

        @if ($document->type !== 'delivery-order')
            <div class="grid p-4 md:grid-cols-2">
                <div></div>
                <div class="bg-slate-100 rounded-lg">
                    <div class="py-2 px-6 flex items-center justify-between gap-2">
                        <div class="font-medium">{{ __('Subtotal') }}</div>
                        <div class="font-medium">{{ currency($document->subtotal, $document->currency) }}</div>
                    </div>

                    @foreach ($document->getTaxes() as $tax)
                        <div class="px-6 flex items-center justify-between gap-2 text-sm">
                            <div>{{ data_get($tax, 'label') }}</div>
                            <div class="font-medium">{{ currency(data_get($tax, 'amount')) }}</div>
                        </div>
                    @endforeach

                    <div class="bg-slate-200 rounded-md m-2 py-2 px-4">
                        <div class="flex items-center justify-between gap-2">
                            <div class="font-bold">{{ __('Grand Total') }}</div>
                            <div class="font-bold">{{ currency($document->grand_total, $document->currency) }}</div>
                        </div>

                        @if ($converted = $document->getConvertedTotal('grand_total'))
                            <div class="text-sm text-right font-medium text-gray-500">
                                {{ currency($converted, account_settings('default_currency')) }}
                            </div>
                        @endif
                    </div>

                    @if ($document->splitted_total)
                        <div class="bg-slate-200 rounded-md m-2 py-2 px-4">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-bold">{{ __('Amount to be Paid') }}</div>
                                <div class="font-bold">{{ currency($document->splitted_total, $document->currency) }}</div>
                            </div>

                            @if ($converted = $document->getConvertedTotal('splitted_total'))
                                <div class="text-sm text-right font-medium text-gray-500">
                                    {{ currency($converted, account_settings('default_currency')) }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="p-4 grid gap-4">
            @if ($note = $document->note)
                <x-form.field label="Note" class="text-sm">
                    {{ $note }}
                </x-form.field>
            @endif

            @if ($footer = $document->footer)
                <x-form.field label="Footer" class="text-sm">
                    {{ $footer }}
                </x-form.field>
            @endif

            @if (!$note && !$footer)
                <div class="text-gray-400 text-center">
                    {{ __('No footer') }}
                </div>
            @endif
        </div>
    </div>
</x-box>
