@if ($slot->isNotEmpty())
    <div x-show="!checkboxes.length" class="{{ $attributes->get('class', 'py-3 px-4 flex flex-wrap items-center justify-between gap-3') }}">
        {{ $slot }}
    </div>
@endif
