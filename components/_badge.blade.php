@php
$status = $attributes->get('status');
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$color = $attributes->get('color') ?? (atom()->isEnum($status) ? $status->color() : get($status, 'color'));

$classes = $attributes->classes()
    ->add('inline-flex items-center justify-center font-medium whitespace-nowrap border max-w-xs')
    ->add(match ($size) {
        'xs' => 'text-xs px-2 rounded',
        'lg' => 'text-base px-3 rounded',
        default => 'text-sm px-2 rounded',
    })
    ->add(match ($color) {
        'red' => 'bg-red-100 text-red-500 border-red-300',
        'yellow' => 'bg-yellow-100 text-yellow-500 border-yellow-300',
        'green' => 'bg-green-100 text-green-500 border-green-300',
        default => 'bg-zinc-200 text-zinc-500 border-transparent'
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['size', 'color', 'status'])
    ;
@endphp

<div {{ $attrs }}>
    <div class="grow truncate">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @elseif (atom()->isEnum($status))
            {{ $status->label() }}
        @endif
    </div>
</div>
