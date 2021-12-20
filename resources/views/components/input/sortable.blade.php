<{{ $el }}
    x-data="inputSortable($wire.get('{{ $attributes->wire('model')->value() }}'), @js($config))"
    wire:ignore
    {{ $attributes }}
>
    {{ $slot }}
</{{ $el }}>