@if ($slot->isNotEmpty())
    <div wire:key="table-toolbar" x-show="!checkboxes.length" {{ $attributes->class([
        'py-3 px-4',
        $attributes->get('class', 'flex flex-wrap items-center gap-3'),
    ]) }}>
        {{ $slot }}
    </div>
@endif
