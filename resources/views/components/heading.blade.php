@php
$apa = $attributes->get('apa', false);
$title = $title ?? $attributes->get('title');
$icon = $icon ?? $attributes->get('icon');
$status = $status ?? $attributes->get('status');
$subtitle = $subtitle ?? $attributes->get('subtitle');
$size = $attributes->size('md');
$except = ['apa', 'title', 'icon', 'status', 'subtitle'];

$margin = $attributes->get('no-margin') ? null : [
    'sm' => 'mb-0',
    'md' => 'mb-2',
    'lg' => 'mb-4',
    'xl' => 'mb-5',
    '2xl' => 'mb-6',
    '3xl' => 'mb-6',
][$size];

$fontsize = [
    'sm' => 'text-base font-semibold',
    'md' => 'text-lg font-medium',
    'lg' => 'text-xl font-semibold',
    'xl' => 'text-2xl font-bold',
    '2xl' => 'text-3xl font-bold',
    '3xl' => 'text-4xl font-extrabold',
    '4xl' => 'text-5xl font-black',
][$size]
@endphp

<div {{ $attributes->class(['flex items-center gap-3 flex-wrap', $margin])->only('class') }}>
    @if ($icon instanceof \Illuminate\View\ComponentSlot)
        <div {{ $icon->attributes->merge(['class' => 'shrink-0']) }}>
            {{ $icon }}
        </div>
    @elseif ($icon)
        <div class="shrink-0 text-gray-400 py-1">
            <x-icon :name="$icon"/>
        </div>
    @endif

    <div class="grow flex flex-col">
        <div class="flex items-center gap-3">
            @if ($title instanceof \Illuminate\View\ComponentSlot)
                {{ $title }}
            @else
                <div class="{{ $fontsize }}">
                    {!! $apa ? str()->apa(tr($title)) : tr($title) !!}
                </div>
            @endif

            @if($status instanceof \Illuminate\View\ComponentSlot)
                {{ $status }}
            @elseif (is_string($status))
                <x-badge :label="$status"/>
            @elseif (is_array($status))
                @foreach (array_filter($status) as $val)
                    <x-badge :badge="$val"/>
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
        <div class="shrink-0 flex items-center gap-2 flex-wrap">
            {{ $slot }}
        </div>
    @endif
</div>