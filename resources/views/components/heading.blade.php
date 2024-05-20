<div {{ $attributes->class([
    'flex items-center gap-3 flex-wrap',
    $attributes->get('class', collect([
        'mb-0' => $attributes->get('sm'),
        'mb-2' => $attributes->get('lg'),
        'mb-4' => $attributes->get('2xl'),
        'mb-6' => $attributes->get('3xl'),
        'mb-6' => $attributes->get('4xl'),
        'mb-4' => true,
    ])->filter()->keys()->first()),
])->only('class') }}>
    @isset($icon)
        <div {{ $icon->attributes->merge(['class' => 'shrink-0']) }}>
            {{ $icon }}
        </div>
    @elseif ($icon = $attributes->get('icon'))
        <div class="shrink-0 text-gray-400 py-1">
            <x-icon :name="$icon"/>
        </div>
    @endisset

    <div class="grow flex flex-col">
        <div class="flex items-center gap-3">
            @isset($title)
                {{ $title }}
            @else
                <div class="{{ collect([
                    'text-base font-semibold' => $attributes->get('sm'),
                    'text-lg font-semibold' => $attributes->get('lg'),
                    'text-2xl font-bold' => $attributes->get('2xl'),
                    'text-3xl font-bold' => $attributes->get('3xl'),
                    'text-4xl font-bold' => $attributes->get('4xl'),
                    'text-xl font-semibold' => true,
                ])->filter()->keys()->first() }}">
                    {!! tr($attributes->get('title')) !!}
                </div>
            @endisset

            @isset($status)
                {{ $status }}
            @elseif ($status = $attributes->get('status'))
                @if (is_string($status)) <x-badge :label="$status"/>
                @elseif (is_array($status))
                    @foreach ($status as $key => $val)
                        <x-badge :label="$val" :color="$key" size="lg"/>
                    @endforeach
                @endif
            @endif
        </div>

        @isset($subtitle)
            {{ $subtitle }}
        @elseif ($subtitle = $attributes->get('subtitle'))
            <div class="font-medium text-gray-500 truncate">
                {!! tr($subtitle) !!}
            </div>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>