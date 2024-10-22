@php
$size = $attributes->get('size');
$color = $attributes->get('color');

$bgcolor = match ($color) {
    'slate' => 'bg-slate-500',
    'gray' => 'bg-gray-500',
    'zinc' => 'bg-zinc-500',
    'neutral' => 'bg-neutral-500',
    'stone' => 'bg-stone-500',
    'red' => 'bg-red-500',
    'orange' => 'bg-orange-500',
    'amber' => 'bg-amber-500',
    'yellow' => 'bg-yellow-500',
    'lime' => 'bg-lime-500',
    'green' => 'bg-green-500',
    'emerald' => 'bg-emerald-500',
    'teal' => 'bg-teal-500',
    'cyan' => 'bg-cyan-500',
    'sky' => 'bg-sky-500',
    'blue' => 'bg-blue-500',
    'indigo' => 'bg-indigo-500',
    'violet' => 'bg-violet-500',
    'purple' => 'bg-purple-500',
    'fuchsia' => 'bg-fuchsia-500',
    'pink' => 'bg-pink-500',
    'rose' => 'bg-rose-500',
    default => '',
};

$classes = $attributes->classes()
    ->add('rounded-full')
    ->add($bgcolor)
    ->add(match ($size) {
        'lg' => 'w-6 h-6',
        'sm' => 'w-3 h-3',
        default => 'w-4 h-4',
    })
    ;

$styles = $attributes->styles();
if (!$bgcolor) $styles->add('background-color', str($color)->start('#'));

$attrs = $attributes
    ->class($classes)
    ->merge(['style' => $styles])
    ->except(['size', 'color'])
    ;
@endphp

<span {{ $attrs }}></span>