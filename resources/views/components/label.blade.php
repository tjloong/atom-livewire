@php
$field = $attributes->field();
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$align = $attributes->get('align');
$required = $attributes->get('required') || (get($this, 'form.required', [])[$field] ?? false);

if (!$label && $field) {
    $label = (string) str(collect(explode('.', $field))->last())
        ->replaceLast('_id', '')
        ->replace('_', ' ')
        ->apa();
}

$align = pick([
    'justify-end' => $align === 'right',
    'justify-center' => $align === 'center',
    'justify-start' => true,
]);

$except = ['icon', 'label', 'align', 'right', 'center', 'left'];
@endphp

@if ($label)
<label {{ $attributes
    ->class(array_filter([
        'font-medium leading-6 text-gray-400 uppercase text-sm inline-flex items-center gap-2',
        $align,
    ]))
    ->merge([
        'for' => $field,
    ])
    ->except($except)
}}>
    @if ($icon) <x-icon :name="$icon"/> @endif
    {!! tr($label) !!}
    @if ($required) <x-icon name="asterisk" class="text-xs text-red-500"/> @endif
</label>
@endif