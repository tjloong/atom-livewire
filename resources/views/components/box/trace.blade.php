@props([
    'getFields' => function() use ($attributes) {
        $data = $attributes->get('data');
        $exclude = (array) $attributes->get('exclude');

        return $data 
            ? collect([
                'requested',
                'approved',
                'rejected',
                'closed',
                'created',
                'updated',
                'deleted',
                'email_sent',
                'login',
                'last_active'
            ])->mapWithKeys(function($col) use ($data) {
                $by = $data->trace($col.'_by.name');
                $at = format_date(data_get($data, $col.'_at'), 'datetime');
                $label = str()->headline(empty($by) ? $col : $col.'_by');

                return [$label => compact('by', 'at')];
            })->filter(fn($val) => !empty(array_filter($val)))
            : [];
    },
])

@if ($fields = $getFields())
    <x-box :heading="$attributes->get('heading', 'Traces')">
        <div class="flex flex-col divide-y">
            @foreach ($fields as $label => $value)
                <x-field :label="$label"
                    :value="empty($value['by']) ? $value['at'] : $value['by']"
                    :small="empty($value['by']) ? null : $value['at']"/>
            @endforeach
        </div>
    </x-box>
@endif
