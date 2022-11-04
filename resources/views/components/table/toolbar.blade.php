<div {{ $attributes->class([
    'py-3 px-4',
    $attributes->get('class', 'flex items-center justify-between gap-2')
]) }}>
    {{ $slot }}
</div>
