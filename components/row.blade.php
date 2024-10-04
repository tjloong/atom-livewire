@php
$classes = $attributes->classes()
    ->add('hover:bg-zinc-50')
    ->add($attributes->hasLike('x-on:click*', 'wire:click*') ? 'cursor-pointer' : '')
    ;
@endphp

<tr {{ $attributes->class($classes) }} data-atom-row>
    {{ $slot }}
</tr>