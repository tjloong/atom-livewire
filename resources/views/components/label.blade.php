@php
$id = $attributes->get('for') ?? $attributes->get('id') ?? $attributes->wire('model')->value();
$icon = $attributes->get('icon');
$align = $attributes->get('align', 'left');
$label = $attributes->get('label') ?? ($id ? str()->apa(collect(explode('.', $id))->last()) : null);
$except = ['icon', 'label', 'align'];
@endphp

@if ($label)
<label {{ $attributes->class([
    'font-medium leading-5 text-gray-400 uppercase text-sm inline-flex items-center gap-2',
    pick([
        'justify-start' => $align === 'left',
        'justify-center' => $align === 'center',
        'justify-end' => $align === 'right',
    ]),
    $attributes->get('class'),
])->except($except) }}>
    @if ($icon) <x-icon :name="$icon"/> @endif
    {!! tr($label) !!}
</label>
@endif