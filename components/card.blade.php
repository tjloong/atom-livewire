@php
$inset = $attributes->get('inset', false);

$classes = $attributes->classes()->add('rounded-xl bg-white border border-zinc-200 shadow-sm');
if (!$inset) $classes->add('p-6');

$attrs = $attributes->class($classes)->except('inset');
@endphp

<div data-atom-card {{ $attrs }}>
    {{ $slot }}
</div>