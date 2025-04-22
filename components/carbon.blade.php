@php
$date = $attributes->get('date');
$utc = $attributes->get('utc');
$format = $attributes->get('format');
$human = $attributes->get('human');
$carbon = is_string($date) ? carbon($date) : $date;

if (!$utc) $carbon->local();

$value = $human ? $carbon->copy()->fromNow() : $carbon->copy()->pretty($format);
$tooltip = $human ? $carbon->copy()->pretty($format) : $carbon->copy()->fromNow();
@endphp

<span x-tooltip="{{ $tooltip }}">
    {{ $value }}
</span>