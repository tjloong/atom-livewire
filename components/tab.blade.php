@php
$tab = $attributes->get('tab');
$rel = $attributes->get('rel') ?? get($tab, 'rel') ?? 'noopener noreferrer nofollow';
$href = $attributes->get('href') ?? get($tab, 'href');
$icon = $attributes->get('icon') ?? get($tab, 'icon');
$label = $attributes->get('label') ?? get($tab, 'label');
$value = $attributes->get('value') ?? get($tab, 'value');
$count = $attributes->get('count') ?? get($tab, 'count');
$target = $attributes->get('target') ?? get($tab, 'target') ?? '_blank';
$element = $href ? 'a' : 'button';

$classes = $attributes->classes()
    ->add('transition-colors duration-200 hover:bg-zinc-50 md:grow')
    ->add($icon ? 'flex items-center gap-2' : '')
    ->add('data-[active]:bg-white')
    ->add('data-[active]:shadow-sm')
    ->add('data-[active]:font-medium')
    ->add('data-[active]:whitespace-nowrap')
    ->add('data-[active]:w-max')
    ->add('data-[inactive]:truncate')
    ->add('data-[inactive]:text-zinc-400')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'href' => $href,
        'type' => $element === 'button' ? 'button' : null,
        'rel' => $element === 'a' ? $rel : null,
        'target' => $element === 'a' ? $target : null,
    ])
    ->except(['tab', 'icon', 'label', 'value', 'count'])
    ;
@endphp

<{{ $element }}
    @if($element === 'button')
    x-on:click="value = {{ js($value) }}"
    x-bind:data-active="value === {{ js($value) }}"
    x-bind:data-inactive="value !== {{ js($value) }}"
    @endif
    {{ $attrs }}>
    @if ($icon)
        <atom:icon :name="$icon" class="shrink-0"/>
    @endif

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @ee(t($label))
    @endif
</{{ $element }}>
