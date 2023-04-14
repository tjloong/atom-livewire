@props([
    'id' => component_id($attributes),
    'model' => $attributes->wire('model')->value(),
    'selected' => data_get($this, $attributes->wire('model')->value(), []),
])

<x-form.field {{ $attributes }}>
    <div class="flex flex-col gap-2 {{
        component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null
    }}">
        @foreach ($attributes->get('options') as $i => $option)
            @php $label = is_string($option) ? $option : data_get($option, 'label') @endphp
            @php $small = is_string($option) ? null : data_get($option, 'small') @endphp
            @php $value = is_string($option) ? $option : data_get($option, 'value') @endphp
            <x-form.checkbox :label="$label" :small="$small"
                wire:click="$set('{{ $model }}', {{ json_encode(
                    in_array($value, $selected)
                        ? collect($selected)->reject($value)->values()->all()
                        : collect($selected)->push($value)->values()->all()
                ) }})"
                :checked="in_array($value, $selected)"
                uuid
            />
        @endforeach
    </div>
</x-form.field>
