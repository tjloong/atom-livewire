@php
$inset = $attributes->get('inset', false);
$subtle = $attributes->get('subtle', false);

$classes = $attributes->classes()
    ->add('relative rounded-lg bg-white border shadow-sm')
    ->add($inset ? '' : 'p-6')
    ->add($subtle ? 'bg-zinc-100 border-transparent' : 'border-zinc-200')
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
    @isset($cover)
        <figure {{ $cover->attributes->class([
            'first:rounded-t-lg last:rounded-b-lg bg-zinc-100 overflow-hidden',
            '[&>*:not(video)]:transistion-transform [&>*:not(video)]:duration-200 [&>*:not(video):hover]:scale-105',
        ]) }}>
            {{ $cover }}
        </figure>
    @endisset

    {{ $slot }}
</div>