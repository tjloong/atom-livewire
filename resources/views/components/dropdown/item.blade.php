@php
$href = $attributes->get('href');
$target = $attributes->get('target');
$el = $href ? 'a' : 'div';
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$count = $attributes->get('count');
$except = ['icon', 'label', 'count'];
@endphp

<{{$el}} {{ $attributes->class([
    'flex items-center gap-3 cursor-pointer hover:bg-slate-50',
    $attributes->get('class', 'py-2 px-4 font-normal'),
])->except($except) }}>
@if ($slot->isNotEmpty())
    {{ $slot }}
@else
    @if ($icon)
        <div class="shrink-0 text-gray-500 text-sm w-4 flex">
            <x-icon :name="$icon" class="m-auto"/>
        </div>
    @endif

    @if ($label)
        <div class="grow">{!! tr($label) !!}</div>
    @endif

    @if ($count)
        <div class="shrink-0 flex">
            <div class="w-5 h-5 m-auto bg-sky-100 rounded-full flex items-center justify-center text-sm text-sky-700">
                {{ $count }}
            </div>
        </div>
    @endif
@endif
</{{$el}}>
