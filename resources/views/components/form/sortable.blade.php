<{{ $el }}
    x-data="sortableInput(
        @if ($attributes->wire('model')->value()) $wire.get('{{ $attributes->wire('model')->value() }}'),
        @elseif ($value) @js($value),
        @else null,
        @endif
        @js($config)
    )"
    wire:ignore
    {{ $attributes }}
>
    {{ $slot }}
</{{ $el }}>
