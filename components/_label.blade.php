@php
$icon = $attributes->get('icon');
$align = $attributes->get('align');

$classes = $attributes->classes()
    ->add('flex items-center gap-2 select-none font-medium leading-6 text-zinc-800')
    ->add(match ($align) {
        'right' => 'justify-end',
        'center' => 'justify-center',
        default => 'justify-start',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['icon', 'align'])
    ;
@endphp

<label {{ $attrs }} data-atom-label>
    @if ($icon)
        <atom:icon :name="$icon" size="15" class="shrink-0"/>
    @endif

    {{ $slot }}
</label>
