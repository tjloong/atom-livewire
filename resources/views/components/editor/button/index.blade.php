@php
$label = $attributes->get('label');
@endphp

<div
    x-tooltip="{{ js(t($label)) }}"
    {{ $attributes->merge(['class' => 'editor-button'])->except('label') }}>
    {{ $slot }}
</div>
