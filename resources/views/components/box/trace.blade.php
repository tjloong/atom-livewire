@props([
    'getFields' => function() use ($attributes) {
        $data = $attributes->get('data');
        $exclude = (array) $attributes->get('exclude');

        return collect([
            'Requested' => [data_get($data, 'requestedBy.name'), format_date(data_get($data, 'requested_at'), 'datetime')],
            'Approved' => [data_get($data, 'approvedBy.name'), format_date(data_get($data, 'approved_at'), 'datetime')],
            'Rejected' => [data_get($data, 'rejectedBy.name'), format_date(data_get($data, 'rejected_at'), 'datetime')],
            'Closed' => [data_get($data, 'closedBy.name'), format_date(data_get($data, 'closed_at'), 'datetime')],
            'Created' => [data_get($data, 'createdBy.name'), format_date(data_get($data, 'created_at'), 'datetime')],
            'Updated' => [data_get($data, 'updatedBy.name'), format_date(data_get($data, 'updated_at'), 'datetime')],
            'Deleted' => [data_get($data, 'deletedBy.name'), format_date(data_get($data, 'deleted_at'), 'datetime')],
            'Email Sent' => [data_get($data, 'emailSentBy.name'), format_date(data_get($data, 'email_sent_at'), 'datetime')],
        ])
            ->filter(fn($val) => isset($val[0]))
            ->filter(fn($val, $key) => !in_array(strtolower($key), $exclude))
            ->toArray();
    },
])

<x-box :header="$attributes->get('header')">
    <div class="flex flex-col divide-y">
        @foreach ($getFields() as $label => $value)
            <x-box.row :label="$label">
                {{ $value[0] }}<br>
                <span class="text-gray-500 text-sm">{{ $value[1] }}</span>
            </x-box.row>
        @endforeach
    </div>
</x-box>
