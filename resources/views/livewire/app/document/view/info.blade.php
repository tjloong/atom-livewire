<div class="flex flex-col divide-y">
    @foreach (array_merge(
        [
            'Number' => $document->number,
            'Issued Date' => format_date($document->issued_at),
            'Status' => ['badge' => $document->status],
        ],

        ($validfor = data_get($document->data, 'valid_for')) ? [
            'Valid For' => __(':valid '.str('day')->plural($validfor), ['valid' => $validfor]),
        ] : [],

        ($ref = $document->reference) ? [
            'Reference' => $ref,
        ] : [],

        ($src = $document->convertedFrom) ? [
            'Convert From' => [
                'value' => $src->type.' #'.$src->number,
                'href' => route('app.document.view', [$src->id]),
            ],
        ] : [],

        $document->type === 'delivery-order' ? [
            'Delivery Channel' => data_get($document->data, 'delivery_channel'),
            'Delivered Date' => format_date($document->delivered_at) ?? '--',
        ] : [],

        ($payterm = $document->formatted_payterm) ? [
            'Payment Term' => $payterm,
        ] : [],

        ($desc = $document->description) ? [
            'Description' => $desc,
        ] : [],
    ) as $key => $val)
        <x-field :label="$key"
            :value="is_string($val) ? $val : data_get($val, 'value')"
            :href="data_get($val, 'href')"
            :badge="data_get($val, 'badge')"
        />
    @endforeach    
</div>
