@props([
    'type' => $attributes->get('type'),
])

<x-form.select
    :options="
        model('label')
            ->when(
                model('label')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount()
            )
            ->when($type, fn($q) => $q->where('type', $type))
            ->oldest('seq')
            ->oldest('id')
            ->get()
            ->transform(fn($label) => [
                'value' => $label->id,
                'label' => $label->locale('name'),
            ])
    "
    {{ $attributes->except('type') }}
/>