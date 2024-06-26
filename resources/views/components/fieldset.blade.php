@php
$cols = $attributes->get('cols');
@endphp

<fieldset>
    <div {{ $attributes->class(array_filter([
        'grid',
        $cols ? "grid-cols-$cols" : null,
        pick([
            'gap-0' => $attributes->get('no-gap'),
            'gap-2' => $attributes->get('tight'),
            // 'gap-2' => $attributes->get('snug'),
            'gap-4' => true,
        ]),
    ])) }}>
        {{ $slot }}
    </div>
</fieldset>