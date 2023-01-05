@extends('atom::pdf.document-layout')

@section('content')
    <table style="margin-bottom: 0.5cm;">
        <tr>
            <td style="width: 60%">
                <div style="
                    font-size: 26pt;
                    line-height: 26pt;
                    font-weight: bold;
                    letter-spacing: 1.5pt;
                    margin-bottom: 0.25cm;
                ">
                    {{ str($document->type)->upper() }}
                </div>

                <div style="margin-bottom: 0.15cm;">
                    <label>{{ 
                        in_array($document->type, ['purchase-order', 'bill'])
                            ? __('Vendor') 
                            : __('Client')
                    }}</label>
                    <div style="font-weight: 700; font-size: 10pt;">
                        {{ $document->name }}
                    </div>
                    {!! nl2br($document->address) !!}
                </div>
            </td>

            <td>
                <table>
                    <tr>
                        <td><label>{{ __('Number') }}</label></td>
                        <td>{{ $document->number }}</td>
                    </tr>
                    <tr>
                        <td><label>{{ __('Date') }}</label></td>
                        <td>{{ format_date($document->issued_at) }}</td>
                    </tr>

                    @if ($document->payterm)
                        <tr>
                            <td><label>{{ __('Payment Terms') }}</label></td>
                            <td>{{ $document->formatted_payterm }}</td>
                        </tr>
                    @endif

                    @if ($valid = data_get($document->data, 'valid_for'))
                        <tr>
                            <td><label>{{ __('Valid Period') }}</label></td>
                            <td>{{ __(':valid day(s)', ['valid' => $valid]) }}</td>
                        </tr>
                    @endif

                    @if ($ref = $document->reference)
                        <tr>
                            <td><label>{{ __('Reference') }}</label></td>
                            <td>{{ $ref }}</td>
                        </tr>
                    @endif

                    @if ($person = $document->person)
                        <tr>
                            <td><label>{{ __('Attention To') }}</label></td>
                            <td>{{ $person }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>

        @if ($desc = $document->description)
            <tr>
                <td colspan="2">
                    <label>{{ __('Description') }}</label>
                    {{ $desc }}
                </td>
            </tr>
        @endif
    </table>

    <div style="margin-bottom: 0.1cm;">
        <table>
            @php $columns = $document->getColumns() @endphp

            <tr>
                <th style="text-align: left;">{{ $columns->get('item_name') }}</th>
                <th width="15%" style="text-align: right;">{{ $columns->get('qty') }}</th>
                @if ($col = $columns->get('price')) <th width="15%" style="text-align: right;">{{ $col }}</th> @endif
                @if ($col = $columns->get('total')) <th width="15%" style="text-align: right;">{{ $col }}</th> @endif
            </tr>
        </table>
    </div>

    @foreach ((
        $document->splittedFrom
            ? $document->splittedFrom->items
            : $document->items
    ) as $item)
        <div style="
            background-color: #f5f7f9;
            margin-bottom: 0.1cm;
            padding: 0.1cm;
        ">
            <table>
                <tr>
                    <td style="font-weight: 700;">{{ $item->name }}</td>
                    <td width="15%" style="text-align: right;">{{ $item->qty }}</td>
                    @if ($columns->get('price'))
                        <td width="15%" style="text-align: right;">{{ currency($item->amount) }}</td>
                    @endif
                    @if ($columns->get('total'))
                        <td width="15%" style="text-align: right;">{{ currency($item->subtotal) }}</td>
                    @endif
                </tr>
            </table>
        </div>

        @if ($columns->get('item_description') && $item->description)
            <div style="
                padding: 0 0.4cm 0.25cm;
                margin-bottom: 0.1cm;
                color: #222;
            ">
                {!! nl2br($item->description) !!}
            </div>
        @endif
    @endforeach

    @if ($document->type !== 'delivery-order')
        <div style="margin-bottom: 0.5cm; margin-left: 50%; background-color: #f5f7f9; padding: 0.1cm;">
            <table style="width: 100%;">
                <tr>
                    <td class="total" style="color: gray;">{{ __('Subtotal') }}</td>
                    <td class="total" style="text-align: right;">{{ currency($document->subtotal, $document->currency) }}</td>
                </tr>

                @if ($taxes = $document->getTaxes())
                    <tr><td colspan="2" style="padding: 0 0 0.2cm;">
                        @foreach ($document->getTaxes() as $tax)
                            <table style="width: 100%">
                                <td class="total tax" style="color: gray;">{{ data_get($tax, 'label') }}</td>
                                <td class="total tax" style="text-align: right;">{{ currency(data_get($tax, 'amount')) }}</td>
                            </table>
                        @endforeach
                    </td></tr>
                @endif

                <tr>
                    <td class="total grand-total" style="color: gray;">{{ __('Grand Total') }}</td>
                    <td class="total grand-total" style="text-align: right;">{{ currency($document->grand_total, $document->currency) }}</td>
                </tr>

                @if ($document->splitted_total)
                    <tr>
                        <td class="total grand-total" style="color: gray;">{{ __('Amount to be Paid') }}</td>
                        <td class="total grand-total" style="text-align: right;">{{ currency($document->splitted_total, $document->currency) }}</td>
                    </tr>
                @endif
            </table>
        </div>
    @endif

    {{-- @if ($tq = data_get($settings, 'tq'))
        <div style="
            font-size: 24pt; 
            font-weight: 700; 
            text-align: right; 
            margin: 0 0.5cm 0.5cm 0.5cm;
        ">
            {!! strtoupper($tq) !!}
        </div>
    @endif --}}

    @if ($note = $document->note)
        <div style="color: #444; font-size: 8pt; padding: 0 0.5cm;">
            {!! nl2br($note) !!}
        </div>
    @endif
@endsection

@section('footer')
    @if ($footer = $document->footer)
        <div style="
            position: fixed;
            bottom: -1.5cm;
            height: 0.75cm;
            padding: 0.15cm 0.75cm;
            font-size: 8pt;
        ">
            {{ $footer }}
        </div>
    @endif    
@endsection
