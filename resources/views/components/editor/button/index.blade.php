@php
$label = $attributes->get('label');
@endphp

<div
    x-tooltip.raw="{{ tr($label) }}"
    {{ $attributes->merge(['class' => 'editor-button'])->except('label') }}>
    {{ $slot }}
</div>
