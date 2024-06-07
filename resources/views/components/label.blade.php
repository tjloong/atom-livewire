@php
$field = $attributes->get('field') ?? $attributes->get('for') ?? $attributes->get('id') ?? $attributes->wire('model')->value();
$icon = $attributes->get('icon');
$label = $attributes->get('label') ?? ($field ? str()->apa(collect(explode('.', $field))->last()) : null);

$align = pick([
    'justify-end' => $attributes->has('right'),
    'justify-center' => $attributes->has('center'),
    'justify-start' => true,
]);

$except = ['icon', 'label', 'align', 'right', 'center', 'left'];
@endphp

@if ($label)
<label {{ $attributes
    ->class([
        'font-medium leading-5 text-gray-400 uppercase text-sm inline-flex items-center gap-2',
        $attributes->get('class', $align),
    ])
    ->merge([
        'for' => $field,
    ])
    ->except($except)
}}>
    @if ($icon) <x-icon :name="$icon"/> @endif
    {!! tr($label) !!}
</label>
@endif