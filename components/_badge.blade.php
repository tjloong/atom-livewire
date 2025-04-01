@php
$status = $attributes->get('status');
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$color = $attributes->get('color') ?? (is_enum($status) ? $status->color() : get($status, 'color'));
$label = $attributes->get('label') ?? (is_enum($status) ? $status->label() : get($status, 'label')) ?? $slot->toHTML();

$classes = $attributes->classes()
    ->add('inline-flex items-center justify-center font-medium whitespace-nowrap border max-w-xs')
    ->add(match ($size) {
        'xs' => 'text-xs px-2 py-0.5 rounded',
        'lg' => 'text-base px-3 py-1 rounded',
        default => 'text-sm px-2 py-0.5 rounded',
    })
    ->add(match ($color) {
        'red' => 'bg-red-100 text-red-500 border-red-300',
        'blue' => 'bg-sky-100 text-sky-500 border-sky-300',
        'yellow' => 'bg-yellow-100 text-yellow-500 border-yellow-300',
        'orange' => 'bg-orange-100 text-orange-500 border-orange-300',
        'green' => 'bg-green-100 text-green-500 border-green-300',
        'purple' => 'bg-purple-100 text-purple-500 border-purple-300',
        'black' => 'bg-black text-zinc-100 border-black',
        'gray' => 'bg-zinc-100 text-zinc-500 border-zinc-200',
        default => 'bg-zinc-100 text-zinc-500 border-zinc-200',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['size', 'color', 'status'])
    ;
@endphp

<div {{ $attrs }} data-atom-badge>
    <div class="grow truncate">
        {!! $label !!}
    </div>
</div>
