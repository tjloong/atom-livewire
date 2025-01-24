@php
$label = $attributes->get('label');
$block = $attributes->get('block');

$classes = $attributes->classes()
    ->add('space-y-1 py-2')
    ->add($block ? '' : 'md:space-y-0 md:grid md:grid-cols-5 md:items-start')
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