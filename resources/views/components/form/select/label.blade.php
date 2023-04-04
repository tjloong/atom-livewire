<x-form.select
    :options="model('label')
        ->readable()
        ->when($attributes->get('type'), fn($q, $type) => $q->where('type', $type))
        ->oldest('seq')
        ->oldest('id')
        ->get()
        ->transform(fn($label) => [
            'value' => $attributes->get('slug') ? $label->slug : $label->id,
            'label' => $label->locale('name'),
        ])
        ->toArray()"
    {{ $attributes->except(['type', 'options']) }}
/>