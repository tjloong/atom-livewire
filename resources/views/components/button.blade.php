@if ($renderable)
    @php
        $el = $attributes->has('href') || $attributes->has('x-bind:href') || $attributes->get(':href')
            ? 'a'
            : 'button'
    @endphp

    <{{ $el }}
        x-data
        {{ $attributes
            ->class([
                $config->styles->size,
                $block ? 'w-full' : '',
                'inline-flex items-center gap-2',
                $attributes->get('class') ?? $config->styles->color,
            ])
            ->merge($el === 'button' ? ['type' => 'button'] : [])
        }}
    >
        @if ($config->icon->name)
            <x-icon :name="$config->icon->name" :size="$config->icon->size"/>
        @endif

        {{ $slot }}
    </{{ $el }}>
@endif
