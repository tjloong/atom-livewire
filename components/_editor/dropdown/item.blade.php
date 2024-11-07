@php
$icon = $icon ?? $attributes->get('icon');
$label = $attributes->get('label');
@endphp

<div {{ $attributes->class([
    'py-2 px-4 flex items-center gap-3',
    $attributes->hasLike('x-on:click*') ? 'cursor-pointer hover:bg-slate-50' : null,
])->except(['icon', 'label']) }}>
    @if ($icon instanceof \Illuminate\View\ComponentSlot)
        <div {{ $icon->attributes->merge(['class' => 'shrink-0']) }}>
            {{ $icon }}
        </div>
    @elseif ($icon)
        <div class="shrink-0 flex items-center justify-center">
            <x-icon :name="$icon"/>
        </div>
    @endif

    @if ($label)
        <div class="grow font-medium">
            {{ tr($label) }}
        </div>
    @else
        <div class="grow">
            {{ $slot }}
        </div>
    @endif
</div>
