@php
$inset = $attributes->get('inset', false);

$classes = $attributes->classes()
    ->add('relative rounded-lg bg-white border border-zinc-200 shadow-sm')
    ->add($inset ? '' : 'p-6')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'data-atom-card' => true,
        'data-atom-card-inset' => $inset ? true : null,
    ])
    ->except('inset')
    ;
@endphp

<div {{ $attrs }}>
    {{ $slot }}
</div>