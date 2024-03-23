@php
    $icon = $attributes->get('icon');
    $label = $attributes->get('label');
    $align = $attributes->get('align', 'left');
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
    ])->except(['icon', 'label']) }}>
        @if ($icon) <x-icon :name="$icon"/> @endif
        {!! tr($label) !!}
    </label>
@endif