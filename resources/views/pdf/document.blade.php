@extends('atom::pdf.layout', ['filename' => $document->number.'.pdf'])

@section('content')
    @if ($letterhead = collect(['pdf.letterhead', 'atom::pdf.letterhead'])
        ->filter(fn($val) => view()->exists($val))
        ->first()
    )
        @include($letterhead, ['document' => $document])
    @endif

    <table class="mb-4">
        <tr>
            <td style="width: 55%">
                <div class="text-4xl font-bold mb-2">
                    {{ str($document->type)->upper() }}
                </div>

                <div class="label mb-1">
                    {{ in_array($document->type, ['purchase-order', 'bill']) ? __('Vendor') : __('Client') }}
                </div>

                <div class="font-bold">
                    {{ $document->name }}
                </div>

                <div class="text-sm">
                    {!! nl2br($document->address) !!}
                </div>
            </td>

            <td class="pl-4">
                <table>
                    @foreach (collect([
                        'Number' => $document->number,
                        'Date' => format_date($document->issued_at),       
                        'Payment Terms' => $document->payterm ? $document->formatted_payterm : false,
                        'Valid Period' => ($valid = data_get($document->data, 'valid_for'))
                            ? __(':valid day(s)', ['valid' => $valid])
                            : false,
                        'Reference' => $document->reference ?: false,
                        'Attention To' => $document->person ?: false,
                    ])->filter(fn($val) => $val !== false)->toArray() as $key => $val)
                        <tr>
                            <td class="label pb-1">{{ __($key) }}</td>
                            <td class="pb-1">{{ $val }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    @if ($desc = $document->description)
        <table class="mb-8">
            <tr>
                <td colspan="2">
                    <div class="label mb-1">{{ __('Description') }}</div>
                    <div class="text-sm">{{ $desc }}</div>
                </td>
            </tr>
        </table>
    @endif

    <div class="mb-2">
        @php $columns = $document->getColumns() @endphp

        <table>
            <tr>
                @foreach (['item_name','qty','price','total'] as $i => $item)
                    @if ($col = $columns->get($item))
                        @if ($i === 0) <td class="text-xs font-bold">{{ strtoupper($col) }}</td>
                        @else <td class="text-xs font-bold text-right" width="15%">{{ strtoupper($col) }}</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        </table>
    </div>

    @foreach ((
        $document->splittedFrom
            ? $document->splittedFrom->items
            : $document->items
    ) as $item)
        <div class="mb-1 bg-gray-100">
            <table>
                <tr>
                    @foreach (array_filter([
                        $item->name,
                        $item->qty,
                        $columns->get('price') ? currency($item->amount) : null,
                        $columns->get('total') ? currency($item->subtotal) : null,
                    ]) as $i => $val)
                        @if ($i === 0) <td class="text-sm font-bold">{{ $val }}</td>
                        @else <td class="text-sm text-right" width="15%">{{ $val }}</td>
                        @endif
                    @endforeach
                </tr>
            </table>
        </div>

        @if ($columns->get('item_description') && $item->description)
            <div class="text-sm px-4 pt-2 pb-4" style="color: #555;">
                {!! nl2br($item->description) !!}
            </div>
        @endif
    @endforeach

    @if (in_array($document->type, ['quotation', 'invoice', 'sales-order', 'purchase-order', 'bill']))
        <div class="ml-auto p-2 bg-gray-100 mb-4" style="width: 50%">
            <table class="px-2 mb-2">
                <tr>
                    <td class="text-lg font-semibold">{{ __('Subtotal') }}</td>
                    <td class="text-lg font-medium text-right">{{ currency($document->subtotal, $document->currency) }}</td>
                </tr>

                @if ($taxes = $document->getTaxes())
                    @foreach ($taxes as $tax)
                        <tr>
                            <td class="font-medium">{{ data_get($tax, 'label') }}</td>
                            <td class="text-right">{{ currency(data_get($tax, 'amount')) }}</td>
                        </tr>
                    @endforeach
                @endif
            </table>

            <table class="bg-gray-200 px-2">
                <tr>
                    <td class="text-lg font-bold">{{ __('Grand Total') }}</td>
                    <td class="text-lg font-bold text-right">{{ currency($document->grand_total, $document->currency) }}</td>
                </tr>

                @if ($document->splitted_total)
                    <tr>
                        <td class="text-lg font-bold">{{ __('Amount To Be Paid') }}</td>
                        <td class="text-lg font-bold text-right">{{ currency($document->splitted_total, $document->currency) }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    @if ($note = $document->note)
        <div class="text-xs px-4">
            {!! nl2br($note) !!}
        </div>
    @endif
@endsection

@if ($footer = $document->footer)
    @section('footer')
        {{ $footer }}
    @endsection
@endif