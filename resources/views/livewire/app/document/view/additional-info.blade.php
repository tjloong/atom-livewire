<x-box header="Information">
    <div class="grid divide-y text-sm">
        @foreach (array_filter([
            ['value' => $document->ownedBy->name, 'label' => 'Owner'],
            ['value' => format_date($document->created_at), 'label' => 'Created Date'],

            $document->last_sent_at
                ? ['value' => format_date($document->last_sent_at), 'label' => 'Last Sent']
                : null,

            $document->labels->count()
                ? ['value' => $document->labels->map(fn($label) => $label->locale('name'))->toArray(), 'label' => 'Labels']
                : null,
        ]) as $info)
            @if (is_array(data_get($info, 'value')))
                <div class="p-3">
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach (data_get($info, 'value') as $tag)
                            <div class="shrink-0">
                                <x-badge :label="$tag" color="blue"/>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <x-box.row :label="data_get($info, 'label')">
                    {{ data_get($info, 'value') }}
                </x-box.row>
            @endif
        @endforeach
    </div>
</x-box>
