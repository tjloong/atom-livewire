@php
$label = $attributes->get('label');
$icon = $attributes->get('icon');
@endphp

<div
    x-tooltip.raw="{{ tr($label) }}"
    class="py-1 px-2 cursor-pointer rounded-md hover:bg-slate-100"
    {{ $attributes->except(['label', 'icon']) }}>
    <x-icon :name="$icon"/>
</div>
