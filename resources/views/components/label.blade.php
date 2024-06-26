@php
$field = $attributes->get('field') ?? $attributes->get('for') ?? $attributes->get('id') ?? $attributes->wire('model')->value();
$icon = $attributes->get('icon');
$label = $attributes->get('label') ?? ($field ? str()->apa(collect(explode('.', $field))->last()) : null);
$required = $attributes->get('required') || (get($this, 'form.required', [])[$field] ?? false);

$align = pick([
    'justify-end' => $attributes->has('right'),
    'justify-center' => $attributes->has('center'),
    'justify-start' => true,
]);

$except = ['icon', 'label', 'align', 'right', 'center', 'left'];
@endphp

@if ($label)
<label {{ $attributes
    ->class(array_filter([
        'font-medium leading-6 text-gray-400 uppercase text-sm inline-flex items-center gap-2',
        $required ? "after:content-['*'] after:ml-0.5 after:mt-1.5 after:text-red-500 after:text-xl" : null,
        $align,
    ]))
    ->merge([
        'for' => $field,
    ])
    ->except($except)
}}>
    @if ($icon) <x-icon :name="$icon"/> @endif
    {!! tr($label) !!}
</label>
@endif