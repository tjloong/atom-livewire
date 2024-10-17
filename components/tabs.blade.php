@php
$tabs = $attributes->get('tabs', []);
$size = $attributes->size();

$classes = $attributes->classes()
    ->add('inline-flex flex-wrap items-center gap-1 select-none p-1 bg-zinc-100 md:flex-nowrap')
    ->add(match ($size) {
        'sm' => 'rounded *:rounded-sm *:text-sm *:py-1 *:px-3',
        default => 'rounded-md *:rounded *:py-1.5 *:px-4',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except('x-data')
    ;
@endphp

<div class="w-full">
    <div x-data="{ value: @entangle($attributes->wire('model')) }" {{ $attrs }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @foreach ($tabs as $tab)
                <atom:tab :tab="$tab"/>
            @endforeach
        @endif
    </div>
</div>