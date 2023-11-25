@php
    $label = $attributes->get('label');
@endphp

<div class="flex items-center gap-3 py-5">
    <div class="grow bg-gray-300 h-px"></div>
    @if ($label)
        <div class="text-sm text-gray-400 font-medium">{{ tr($label) }}</div>
        <div class="grow bg-gray-300 h-px"></div>
    @endif
</div>
