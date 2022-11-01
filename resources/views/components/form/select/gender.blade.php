<x-form.select
    :label="$attributes->get('label', 'Gender')"
    :options="collect(['male', 'female'])->map(fn($val) => [
        'value' => $val, 
        'label' => __(str()->title($val)),
    ])->toArray()"
    {{ $attributes->except(['label', 'options']) }}
/>
