@php
$attrs = $attributes
    ->class(['border-l border-zinc-200 px-1'])
    ->except('sortable');
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>
