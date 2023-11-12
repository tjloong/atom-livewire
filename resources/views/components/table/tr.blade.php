<tr {{ $attributes->class([
    'border-b last:border-0 hover:bg-slate-50',
    $attributes->hasLike('wire:*', 'x-*') ? 'cursor-pointer' : null,
]) }}>
    {{ $slot }}
</tr>