@props([
    'href' => $attributes->get('href'),
    'icon' => $attributes->get('icon'),
    'label' => $attributes->get('label'),
])

@if ($slot->isNotEmpty())
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@elseif ($href)
    <a href="{{ $href }}" {{ $attributes->class([
        'py-2 px-4 flex items-center gap-3 text-gray-800 font-normal hover:bg-slate-100'
    ])->except(['href', 'icon', 'label']) }}>
        @if ($icon) <x-icon :name="$icon" class="shrink-0 text-gray-500 text-sm w-4"/> @endif
        @if ($label) {!! tr($label) !!} @endif
    </a>
@else
    <div {{ $attributes->class([
        'py-2 px-4 flex items-center gap-3 text-gray-800 cursor-pointer hover:bg-slate-100'
    ])->except(['href', 'icon', 'label']) }}>
        @if ($icon) <x-icon :name="$icon" class="shrink-0 text-gray-500 text-sm w-4"/> @endif
        @if ($label) {!! tr($label) !!} @endif
    </div>
@endif