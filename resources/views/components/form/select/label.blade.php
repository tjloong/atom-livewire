@props([
    'type' => $attributes->get('type'),
])

<x-form.select
    :options="
        model('label')
            ->when(
                model('label')->enabledHasTenantTrait,
                fn($q) => $q->belongsToTenant()
            )
            ->when($type, fn($q) => $q->where('type', $type))
            ->oldest('seq')
            ->oldest('id')
            ->get()
            ->transform(fn($label) => [
                'value' => $attributes->get('slug') ? $label->slug : $label->id,
                'label' => $label->locale('name'),
            ])
    "
    {{ $attributes->except('type') }}
/>