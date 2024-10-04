@php
$name = $attributes->get('name')
    ?? collect($attributes->whereDoesntStartWith('x-', 'wire:')->getAttributes())
        ->keys()
        ->reject(fn($key) => in_array($key, ['class', 'style', 'badge', 'size']))
        ->first();

$size = $attributes->get('size', 16);
$badge = $badge ?? $attributes->get('badge');
$content = $slot->isEmpty() ? atom()->icon($name) : $slot;

$classes = $attributes->classes()
    ->add('*:w-full *:h-full')
    ->add('inline-flex items-center justify-center')
    ;

$styles = $attributes->styles()
    ->add('width', str($size)->finish('px'))
    ->add('height', str($size)->finish('px'));

$attrs = $attributes
    ->class($classes)
    ->merge(['style' => $styles])
    ->except(['name', 'size', 'badge', $name]);
@endphp

@if ($badge)
    <span class="inline-flex items-center justify-center">
        <span {{ $attrs }}>
            {!! $content !!}
        </span>

        <span class="inline-flex items-center justify-center rounded-full min-w-5 w-max px-1 h-5 text-xs font-medium leading-none -ml-2 {{
            $badge instanceof \Illuminate\View\ComponentSlot
            ? $badge->attributes->get('class')
            : 'bg-primary-100 text-primary border shadow-sm'
        }}">
            {{ $badge }}
        </span>
    </span>
@else
    <span {{ $attrs }}>
        {!! $content !!}
    </span>
@endif
