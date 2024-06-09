@php
$field = $attributes->get('field');
$label = $attributes->get('label') ?? ($field ? optional($errors)->get($field) : null);
@endphp

@if ($label)
<div {{ $attributes->class(['error text-red-500 text-sm font-medium']) }}>
    @if (is_string($label)) {!! tr($label) !!}
    @elseif (is_array($label) && count($label) === 1) {!! tr(head($label)) !!}
    @elseif (is_array($label))
        <ul class="list-disc list-inside">
            @foreach ($label as $val)
                <li>{!! tr($val) !!}</li>
            @endforeach
        </ul>
    @endif
</div>
@endif