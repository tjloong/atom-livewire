@if ($slot->isNotEmpty())
    <div class="{{ $attributes->get('class', 'py-3 px-4 flex items-center justify-between gap-2') }}">
        {{ $slot }}
    </div>
@endif
