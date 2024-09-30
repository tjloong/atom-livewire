@php
$name = $attributes->get('name')
    ?? collect($attributes->whereDoesntStartWith('x-', 'wire:')->getAttributes())
        ->keys()
        ->reject(fn($key) => in_array($key, ['class', 'style', 'size']))
        ->first();

$size = $attributes->get('size', 15);
$content = $slot->isEmpty() ? atom()->icon($name) : $slot;

$classes = $attributes->classes()
    ->add('*:w-full *:h-full')
    ->add('inline-flex items-center justify-center');

$styles = $attributes->styles()
    ->add('width', str($size)->finish('px'))
    ->add('height', str($size)->finish('px'));

$attrs = $attributes
    ->class($classes)
    ->merge(['style' => $styles])
    ->except(['name', 'size', $name]);
@endphp

@if (isset($badge))
    <span class="inline-flex items-center justify-center">
        <span {{ $attrs }}>
            {!! $content !!}
        </span>
        
        <span {{ $badge->attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-full min-w-5 w-max px-1 h-5 text-xs font-medium leading-none -ml-2',
        ]) }}>
            {{ $badge }}
        </span>
    </span>
@else
    <span {{ $attrs }}>
        {!! $content !!}
    </span>
@endif
