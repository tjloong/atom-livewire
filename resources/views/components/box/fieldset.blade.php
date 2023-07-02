@props([
    'fields' => $attributes->get('fields', []),
])

<x-box>
    <div class="flex flex-col divide-y">
        @foreach (collect($fields)->filter() as $field)
            @if (
                (data_get($field, 'type') === 'items' && ($items = data_get($field, 'value')))
                || ($items = data_get($field, 'items'))
            )
                <div class="py-2 px-4">
                    <x-form.items :label="data_get($field, 'label')">
                        <div class="grid divide-y">
                            @foreach ($items as $item)
                                <div class="text-sm py-2 px-4">
                                    @if (is_string($item)) {!! $item !!}
                                    @elseif ($label = data_get($item, 'label'))
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="text-gray-500 font-medium">{{ $label }}</div>
                                            <div>{!! data_get($item, 'value') !!}</div>
                                        </div>
                                    @elseif ($icon = data_get($item, 'icon')) 
                                        <div class="flex gap-2">
                                            <x-icon :name="$icon" size="12" class="shrink-0 text-gray-400 mt-1"/>
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
            @else
                <x-box.row :label="data_get($field, 'label')">
                    {!! data_get($field, 'value') !!}
                </x-box.row>
            @endif
        @endforeach
    </div>
</x-box>

