@php
$size = $attributes->get('size');

$classes = $attributes->classes()
    ->add('w-full')
    ->add(match ($size) {
        'lg' => 'py-4 text-lg',
        'xl' => 'py-8 text-xl',
        'sm' => 'text-sm',
        default => 'py-0',
    })
    ;

if ($slot->isNotEmpty()) $classes->add('flex items-center');
@endphp

<div role="none" data-atom-separator {{ $attributes->class($classes) }}>
    <div class="border-0 bg-zinc-800/15 h-px w-full"></div>

    @if ($slot->isNotEmpty())
        <span class="shrink mx-4 font-medium text-zinc-400 whitespace-nowrap text-center">{{ $slot }}</span>
        <div class="border-0 bg-zinc-800/15 h-px w-full"></div>
    @endif
</div>
