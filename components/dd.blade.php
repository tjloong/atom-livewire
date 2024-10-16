@php
$label = $attributes->get('label');

$classes = $attributes->classes()
    ->add('space-y-1 py-3 md:space-y-0 md:grid md:grid-cols-5 md:items-start')
    ->add('[[data-atom-card]>&:hover]:bg-zinc-50')
    ->add('[[data-atom-card]:not([data-atom-card-inset])>&]:-mx-6')
    ->add('[[data-atom-card]:not([data-atom-card-inset])>&]:px-6')
    ;

$attrs = $attributes
    ->class($classes)
    ->except('label')
    ;
@endphp

<div data-atom-dd {{ $attrs }}>
    <dt class="md:col-span-2 text-zinc-500">@t($label)</dt>
    <dd class="md:col-span-3">{{ $slot }}</dd>
</div>