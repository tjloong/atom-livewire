@props([
    'fields' => $attributes->get('fields', []),
])

<x-box>
    <div class="flex flex-col divide-y">
        @foreach (collect($fields)->filter() as $field)
            @php $type = data_get($field, 'type') @endphp
            @php $label = data_get($field, 'label') @endphp
            @php $value = data_get($field, 'value') @endphp

            @if ($type === 'items')
                <div class="py-2 px-4">
                    <x-form.items :label="$label">
                        <div class="grid divide-y">
                            @foreach ($value as $item)
                                <div class="text-sm py-2 px-4">
                                    @if (is_string($item)) {!! $item !!}
                                    @elseif ($itemlabel = data_get($item, 'label'))
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="text-gray-500 font-medium">{{ $itemlabel }}</div>
                                            <div>{!! data_get($item, 'value') !!}</div>
                                        </div>
                                    @elseif ($itemicon = data_get($item, 'icon')) 
                                        <div class="flex gap-2">
                                            <x-icon :name="$itemicon" size="12" class="shrink-0 text-gray-400 mt-1"/>
                                            <div>{!! data_get($item, 'value') !!}</div>
                                        </div>
                                    @else
                                        {!! data_get($item, 'value') !!}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </x-form.items>
                </div>
            @elseif (in_array($type, ['badge', 'status']))
                <x-box.row :label="$label">
                    @if (is_string($value)) 
                        <x-badge :label="$value"/>
                    @elseif (is_array($value))
                        <div class="inline-flex items-center gap-2 flex-wrap">
                            @foreach ($value as $key => $val)
                                <x-badge :label="$val" :color="$key"/>
                            @endforeach
                        </div>
                    @elseif (isset($value->value))
                        <x-badge :label="$value->value"/>
                    @endif
                </x-box.row>
            @else
                <x-box.row :label="$label">
                    {!! $value !!}
                </x-box.row>
            @endif
        @endforeach
    </div>
</x-box>

