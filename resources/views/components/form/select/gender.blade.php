<x-form.select
    :label="$attributes->get('label', 'Gender')"
    :options="collect(['male', 'female'])->map(fn($val) => [
        'value' => $val, 
        'label' => str()->title($val),
    ])->toArray()"
    {{ $attributes->except(['label', 'options']) }}
/>
