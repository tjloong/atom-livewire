@php
$noApa = $attributes->get('no-apa', false);
$title = $title ?? $attributes->get('title');
$status = $status ?? $attributes->get('status');
$subtitle = $subtitle ?? $attributes->get('subtitle');
@endphp

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
            @if ($title instanceof \Illuminate\View\ComponentSlot)
                {{ $title }}
            @else
                <div class="{{ pick([
                    'text-base font-semibold' => $attributes->get('sm'),
                    'text-lg font-semibold' => $attributes->get('lg'),
                    'text-2xl font-bold' => $attributes->get('2xl'),
                    'text-3xl font-bold' => $attributes->get('3xl'),
                    'text-4xl font-bold' => $attributes->get('4xl'),
                    'text-5xl font-bold' => $attributes->get('5xl'),
                    'text-xl font-semibold' => true,
                ]) }}">
                    {!! $noApa ? tr($title) : str()->apa(tr($title)) !!}
                </div>
            @endif

            @if($status instanceof \Illuminate\View\ComponentSlot)
                {{ $status }}
            @elseif (is_string($status))
                <x-badge :label="$status"/>
            @elseif (is_array($status))
                @foreach ($status as $key => $val)
                    <x-badge :label="$val" :color="$key"/>
                @endforeach
            @endif
        </div>

        @if($subtitle instanceof \Illuminate\View\ComponentSlot)
            {{ $subtitle }}
        @elseif ($subtitle)
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