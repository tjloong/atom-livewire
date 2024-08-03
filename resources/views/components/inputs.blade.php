@php
$title = $attributes->getAny('title', 'heading');
$cols = $attributes->get('cols') === true ? 2 : $attributes->get('cols', 1);
$nopadding = $attributes->get('no-padding');
$except = ['title', 'heading', 'cols'];
@endphp

<div>
    @if ($title instanceof \Illuminate\View\ComponentSlot)
        {{ $title }}
    @elseif ($title)
        <div class="py-3 flex items-center gap-3 {{ $nopadding ? '' : 'px-5' }}">
            <div class="shrink-0 font-medium">
                {!! tr($title) !!}
            </div>

            <div class="grow h-px bg-gray-200"></div>
        </div>
    @endif

    <div {{ $attributes->class([
        "grid gap-5 md:grid-cols-$cols",
        $nopadding ? '' : 'p-5',
    ])->except($except) }}>
        {{ $slot }}
    </div>
</div>