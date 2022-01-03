<{{ $el }}
    x-data="inputSortable(
        @if ($attributes->wire('model')->value()) $wire.get('{{ $attributes->wire('model')->value() }}'),
        @else null,
        @endif
        @js($config)
    )"
    wire:ignore
    {{ $attributes }}
>
    {{ $slot }}
</{{ $el }}>