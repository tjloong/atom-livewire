<div class="p-4">
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
            <x-form.field :label="str($convertedFrom->type)->headline()">
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