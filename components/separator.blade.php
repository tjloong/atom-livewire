@php
$classes = $attributes->classes()->add('w-full');
if ($slot->isNotEmpty()) $classes->add('flex items-center');
@endphp

<div role="none" data-atom-separator {{ $attributes->class($classes) }}>
    <div class="border-0 bg-zinc-800/15 h-px w-full"></div>

    @if ($slot->isNotEmpty())
        <span class="shrink mx-4 font-medium text-sm text-zinc-400 whitespace-nowrap">{{ $slot }}</span>
        <div class="border-0 bg-zinc-800/15 h-px w-full"></div>
    @endif
</div>
